<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/** MVP-3 privacy gate: privacy page reachable + no secret/PII in application logs. */
class PrivacyGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_page_is_reachable_and_contains_promise_sections(): void
    {
        $this->get('/privasi')->assertOk()
            ->assertSee('Data apa yang disimpan')
            ->assertSee('Siapa yang bisa mengakses')
            ->assertSee('Cara meminta penghapusan');
    }

    public function test_privacy_page_is_linked_from_invitation_page(): void
    {
        $owner = User::factory()->create();
        $event = $owner->events()->create(['name' => 'Pernikahan Uji', 'starts_at' => '2027-02-14T10:00']);

        $this->get('/u/'.$event->invitation_token)->assertOk()->assertSee('Privasi & data Anda');
    }

    public function test_privacy_page_is_linked_from_landing_page(): void
    {
        $this->get('/')->assertOk()->assertSee('Kebijakan privasi');
    }

    public function test_invitation_access_and_rsvp_logs_contain_no_token_or_pii(): void
    {
        $logPath = storage_path('logs/laravel.log');
        File::delete($logPath);

        $owner = User::factory()->create();
        $event = $owner->events()->create([
            'name' => 'Pernikahan Rahasia',
            'starts_at' => '2027-02-14T10:00',
        ]);
        $token = $event->invitation_token;

        $this->get('/u/'.$token)->assertOk();
        $this->post('/u/'.$token.'/rsvp', [
            'name' => 'Tamu Rahasia',
            'status' => 'attending',
            'message' => 'Akan membawa keluarga',
        ])->assertRedirect();

        $logContent = (string) File::get($logPath);

        // Instrumented lines present...
        $this->assertStringContainsString('invitation.view', $logContent);
        $this->assertStringContainsString('rsvp.submitted', $logContent);

        // ...but the invitation token and guest PII are NOT printed.
        $this->assertStringNotContainsString($token, $logContent);
        $this->assertStringNotContainsString('Tamu Rahasia', $logContent);
        $this->assertStringNotContainsString('Akan membawa keluarga', $logContent);
        $this->assertStringNotContainsString('Pernikahan Rahasia', $logContent);
    }

    public function test_photo_upload_log_contains_no_token_or_filename(): void
    {
        $logPath = storage_path('logs/laravel.log');
        File::delete($logPath);

        $owner = User::factory()->create();
        $event = $owner->events()->create(['name' => 'Pernikahan Uji', 'starts_at' => '2027-02-14T10:00']);
        $token = $event->invitation_token;

        $this->post('/u/'.$token.'/photos', [
            'photo' => \Illuminate\Http\UploadedFile::fake()->image('foto-pengantin.jpg'),
        ])->assertRedirect();

        $logContent = (string) File::get($logPath);

        $this->assertStringContainsString('photo.uploaded', $logContent);
        $this->assertStringNotContainsString($token, $logContent);
        $this->assertStringNotContainsString('foto-pengantin.jpg', $logContent);
    }
}
