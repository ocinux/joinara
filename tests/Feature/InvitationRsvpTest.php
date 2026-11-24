<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationRsvpTest extends TestCase
{
    use RefreshDatabase;

    /** Host event + one guest with a personal code (host-side distribution lands in MVP-5). */
    private function eventWithGuest(string $code = 'TAMUBUDI001'): array
    {
        $event = Event::create([
            'owner_id' => User::factory()->create()->id,
            'name' => 'Pernikahan Sari & Bayu',
            'starts_at' => '2027-02-14T10:00',
            'location' => 'Yogyakarta',
            'partner_1' => 'Sari',
            'partner_2' => 'Bayu',
        ]);

        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Budi',
            'unique_code' => $code,
        ]);

        return [$event, $guest];
    }

    public function test_rsvp_form_shows_for_valid_personal_code(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->get("/u/{$event->invitation_token}?code={$guest->unique_code}")
            ->assertOk()
            ->assertSee('Konfirmasi Kehadiran')
            ->assertSee('Apakah Anda hadir?')
            ->assertSee('Kirim Konfirmasi')
            ->assertSee($guest->unique_code, false); // hidden input carries the code
    }

    public function test_link_without_code_shows_notice_and_no_form(): void
    {
        [$event] = $this->eventWithGuest();

        $this->get("/u/{$event->invitation_token}")
            ->assertOk()
            ->assertSee('Undangan ini bersifat personal')
            ->assertDontSee('Kirim Konfirmasi');
    }

    public function test_link_with_unknown_code_shows_notice_and_no_form(): void
    {
        [$event] = $this->eventWithGuest();

        $this->get("/u/{$event->invitation_token}?code=TIDAKADA")
            ->assertOk()
            ->assertSee('Undangan ini bersifat personal')
            ->assertDontSee('Kirim Konfirmasi');
    }

    public function test_valid_rsvp_is_recorded_and_linked_to_event_and_guest(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi Pratama',
            'attendance' => 'hadir',
            'party_size' => '3',
            'notes' => 'Insya Allah hadir',
        ])->assertRedirect("/u/{$event->invitation_token}?code={$guest->unique_code}");

        $this->assertDatabaseHas('rsvps', [
            'guest_id' => $guest->id,
            'event_id' => $event->id,
            'status' => 'attending',
            'plus_ones' => 2, // party_size 3 termasuk pengirim -> plus_ones 2
            'message' => 'Insya Allah hadir',
        ]);

        $this->get("/u/{$event->invitation_token}?code={$guest->unique_code}")
            ->assertOk()
            ->assertSee('Terima kasih, Budi Pratama!')
            ->assertSee('Jumlah tamu: 3');
    }

    public function test_declined_rsvp_ignores_party_size(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi',
            'attendance' => 'tidak',
            'party_size' => '5',
        ])->assertRedirect();

        $this->assertDatabaseHas('rsvps', [
            'guest_id' => $guest->id,
            'status' => 'declined',
            'plus_ones' => 0,
        ]);
    }

    public function test_duplicate_submit_updates_not_duplicates(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $payload = [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi',
            'attendance' => 'hadir',
            'party_size' => '2',
        ];

        $this->post("/u/{$event->invitation_token}/rsvp", $payload)->assertRedirect();
        $this->post("/u/{$event->invitation_token}/rsvp", $payload)->assertRedirect();

        $this->assertSame(1, Rsvp::where('guest_id', $guest->id)->count());

        // Ubah jawaban -> baris yang sama diperbarui, bukan tambah.
        $this->post("/u/{$event->invitation_token}/rsvp", array_merge($payload, ['attendance' => 'tidak']))
            ->assertRedirect();
        $this->assertSame(1, Rsvp::where('guest_id', $guest->id)->count());
        $this->assertDatabaseHas('rsvps', ['guest_id' => $guest->id, 'status' => 'declined']);
    }

    public function test_post_without_code_is_rejected_with_notice(): void
    {
        [$event] = $this->eventWithGuest();

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'guest_name' => 'Budi',
            'attendance' => 'hadir',
            'party_size' => '1',
        ])->assertRedirect("/u/{$event->invitation_token}")
            ->assertSessionHasErrors(['invitation_code' => 'Undangan ini bersifat personal. Mohon gunakan tautan undangan yang dikirim oleh pengundang.'], errorBag: 'rsvp');

        $this->assertSame(0, Rsvp::count());
    }

    public function test_validation_errors_on_missing_fields(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'invitation_code' => $guest->unique_code,
        ])->assertSessionHasErrors(['guest_name', 'attendance'], errorBag: 'rsvp');

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi',
            'attendance' => 'hadir',
            'party_size' => '11', // di luar 1..10
        ])->assertSessionHasErrors('party_size', errorBag: 'rsvp');

        $this->assertSame(0, Rsvp::count());
    }

    public function test_invalid_token_returns_404(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->post('/u/totallyBogusToken1234567890abcdef/rsvp', [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi',
            'attendance' => 'hadir',
            'party_size' => '1',
        ])->assertNotFound();
    }

    public function test_already_confirmed_guest_sees_readonly_panel_on_revisit(): void
    {
        [$event, $guest] = $this->eventWithGuest();

        $this->post("/u/{$event->invitation_token}/rsvp", [
            'invitation_code' => $guest->unique_code,
            'guest_name' => 'Budi',
            'attendance' => 'hadir',
            'party_size' => '2',
        ])->assertRedirect();

        // Revisit (flash sukses sudah terkonsumsi GET pertama) -> panel sudah ter-isi, bukan form ulang.
        $this->get("/u/{$event->invitation_token}?code={$guest->unique_code}")->assertOk();
        $this->get("/u/{$event->invitation_token}?code={$guest->unique_code}")
            ->assertOk()
            ->assertSee('Anda sudah konfirmasi kehadiran')
            ->assertDontSee('Kirim Konfirmasi');
    }
}
