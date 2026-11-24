<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Media;
use App\Models\Rsvp;
use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MigrationFromZeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_run_clean_from_zero_on_sqlite(): void
    {
        // RefreshDatabase already wipes; this test proves migrations apply cleanly.
        $this->artisan('migrate')->assertExitCode(0);
        $this->assertDatabaseCount('events', 0);
    }

    public function test_core_models_can_be_created_and_related(): void
    {
        $event = Event::create([
            'owner_id' => User::factory()->create()->id,
            'name' => 'Wedding A & B',
            'starts_at' => now()->addWeek(),
            'location' => 'Bandung',
        ]);

        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Budi',
            'phone' => '08123456789',
            'email' => 'budi@example.test',
            'unique_code' => 'WDBUDI01',
        ]);

        $rsvp = Rsvp::create([
            'guest_id' => $guest->id,
            'event_id' => $event->id,
            'status' => 'attending',
            'plus_ones' => 1,
            'message' => 'Insya Allah hadir',
        ]);

        $media = Media::create([
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'path' => 'photos/budi-1.jpg',
            'status' => 'pending',
        ]);

        $checkin = CheckIn::create([
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'method' => 'qr',
            'checked_in_at' => now(),
        ]);

        $this->assertDatabaseHas('rsvps', ['id' => $rsvp->id, 'status' => 'attending']);
        $this->assertDatabaseHas('media', ['id' => $media->id, 'status' => 'pending']);
        $this->assertDatabaseHas('check_ins', ['id' => $checkin->id, 'method' => 'qr']);

        $this->assertEquals($event->id, $rsvp->guest->event->id);
    }
}
