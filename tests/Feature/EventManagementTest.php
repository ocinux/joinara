<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Pernikahan Rina & Dimas',
            'starts_at' => '2027-01-10T09:00',
            'location' => 'Bandung',
            'partner_1' => 'Rina',
            'partner_2' => 'Dimas',
        ], $overrides);
    }

    public function test_owner_can_create_event(): void
    {
        $owner = User::factory()->create();

        $response = $this->actingAs($owner)->post('/events', $this->validPayload());

        $response->assertRedirect();
        $event = Event::where('name', 'Pernikahan Rina & Dimas')->firstOrFail();
        $this->assertSame($owner->id, $event->owner_id);
    }

    public function test_owner_can_update_own_event(): void
    {
        $owner = User::factory()->create();
        $event = $owner->events()->create($this->validPayload());

        $this->actingAs($owner)
            ->put("/events/{$event->id}", $this->validPayload(['name' => 'Pernikahan Rina & Dimas (baru)', 'location' => 'Jakarta']))
            ->assertRedirect("/events/{$event->id}");

        $this->assertDatabaseHas('events', ['id' => $event->id, 'name' => 'Pernikahan Rina & Dimas (baru)', 'location' => 'Jakarta']);
    }

    public function test_owner_can_delete_event(): void
    {
        $owner = User::factory()->create();
        $event = $owner->events()->create($this->validPayload());

        $this->actingAs($owner)->delete("/events/{$event->id}")->assertRedirect('/events');

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_store_validation_rejects_missing_name_or_date(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post('/events', ['name' => ''])->assertSessionHasErrors(['name']);
        $this->post('/events', $this->validPayload(['starts_at' => 'not-a-date']))->assertSessionHasErrors(['starts_at']);
        $this->assertDatabaseCount('events', 0);
    }

    public function test_events_of_other_owners_are_invisible(): void
    {
        $ownerA = User::factory()->create();
        $ownerB = User::factory()->create();
        $eventA = $ownerA->events()->create($this->validPayload());
        $eventB = $ownerB->events()->create($this->validPayload(['name' => 'Event B']));

        // Index only shows own events.
        $indexA = $this->actingAs($ownerA)->get('/events');
        $indexA->assertOk()->assertSee($eventA->name)->assertDontSee('Event B');
        $indexB = $this->actingAs($ownerB)->get('/events');
        $indexB->assertOk()->assertSee($eventB->name)->assertDontSee($eventA->name);

        // Direct access to someone else's event -> 404, not visible.
        $this->actingAs($ownerB)->get("/events/{$eventA->id}")->assertNotFound();
        $this->actingAs($ownerB)->put("/events/{$eventA->id}", $this->validPayload())->assertNotFound();
        $this->actingAs($ownerB)->delete("/events/{$eventA->id}")->assertNotFound();
    }
}
