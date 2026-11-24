<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * MVP-3 privacy gate: rate limits on public endpoints.
 * Limits (initial values, per founder clarification on issue #4):
 * invitation view 120/min/IP · check-in 60/min/IP · RSVP 10/min/IP · upload 20/min/IP.
 */
class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $owner = User::factory()->create();
        $this->event = $owner->events()->create([
            'name' => 'Pernikahan Sari & Bayu',
            'starts_at' => '2027-02-14T10:00',
            'location' => 'Yogyakarta',
            'partner_1' => 'Sari',
            'partner_2' => 'Bayu',
        ]);
    }

    private function rsvpPayload(): array
    {
        return ['name' => 'Tamu Uji', 'status' => 'attending', 'plus_ones' => 0, 'message' => ''];
    }

    public function test_invitation_view_is_limited_to_120_per_minute(): void
    {
        for ($i = 0; $i < 120; $i++) {
            $this->get('/u/'.$this->event->invitation_token)->assertOk();
        }

        $this->get('/u/'.$this->event->invitation_token)->assertStatus(429);
    }

    public function test_checkin_view_is_limited_to_60_per_minute(): void
    {
        for ($i = 0; $i < 60; $i++) {
            $this->get('/u/'.$this->event->invitation_token.'/checkin')->assertOk();
        }

        $this->get('/u/'.$this->event->invitation_token.'/checkin')->assertStatus(429);
    }

    public function test_rsvp_submit_is_limited_to_10_per_minute(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/u/'.$this->event->invitation_token.'/rsvp', $this->rsvpPayload())
                ->assertRedirect();
        }

        $this->post('/u/'.$this->event->invitation_token.'/rsvp', $this->rsvpPayload())
            ->assertStatus(429);
    }

    public function test_photo_upload_is_limited_to_20_per_minute(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->post('/u/'.$this->event->invitation_token.'/photos', [])
                ->assertSessionHasErrors('photo'); // 422: validation fires before the 21st request
        }

        $this->post('/u/'.$this->event->invitation_token.'/photos', [])
            ->assertStatus(429);
    }
}
