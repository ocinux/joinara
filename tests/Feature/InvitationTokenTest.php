<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTokenTest extends TestCase
{
    use RefreshDatabase;

    private function eventOf(User $owner): Event
    {
        return $owner->events()->create([
            'name' => 'Pernikahan Sari & Bayu',
            'starts_at' => '2027-02-14T10:00',
            'location' => 'Yogyakarta',
            'partner_1' => 'Sari',
            'partner_2' => 'Bayu',
        ]);
    }

    public function test_token_is_random_long_and_not_the_sequential_id(): void
    {
        $owner = User::factory()->create();
        $event = $this->eventOf($owner);

        $this->assertNotEmpty($event->invitation_token);
        $this->assertSame(32, strlen($event->invitation_token));
        $this->assertNotSame((string) $event->id, $event->invitation_token);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{32}$/', $event->invitation_token);

        $second = $this->eventOf($owner);
        $this->assertNotSame($event->invitation_token, $second->invitation_token);
    }

    public function test_invitation_page_is_public_and_reachable_by_token(): void
    {
        $event = $this->eventOf(User::factory()->create());

        $this->get("/u/{$event->invitation_token}")
            ->assertOk()
            ->assertSee($event->name)
            ->assertSee('Sari');
    }

    public function test_invitation_page_does_not_expose_the_numeric_id_route(): void
    {
        $event = $this->eventOf(User::factory()->create());

        $this->get("/u/{$event->id}")->assertNotFound();
        $this->get("/u/{$event->invitation_token}")->assertOk();
    }

    public function test_invalid_token_returns_clear_404_not_500(): void
    {
        $this->eventOf(User::factory()->create());

        $response = $this->get('/u/totallyBogusToken1234567890abcdef');
        $response->assertNotFound();
        $this->assertSame(404, $response->status());
        $response->assertSee('Tautan tidak ditemukan');

        $this->get('/u/totallyBogusToken1234567890abcdef/checkin')
            ->assertNotFound()
            ->assertSee('Tautan tidak ditemukan');
    }

    public function test_svg_qr_is_rendered_for_the_checkin_link(): void
    {
        $owner = User::factory()->create();
        $event = $this->eventOf($owner);
        $checkinUrl = route('invitation.checkin', $event->invitation_token);

        // Qr::svg should not be empty / throw.
        $svg = \App\Support\Qr::svg($checkinUrl);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('d="M', $svg);

        $response = $this->actingAs($owner)->get("/events/{$event->id}");
        $response->assertOk();
        // Blade's {{ }} escapes; search the escaped version.
        $response->assertSee('&lt;svg', false);
        $response->assertSee(htmlspecialchars($checkinUrl), false);
    }

    public function test_checkin_page_renders_for_valid_token(): void
    {
        $event = $this->eventOf(User::factory()->create());

        $this->get("/u/{$event->invitation_token}/checkin")
            ->assertOk()
            ->assertSee($event->name);
    }
}
