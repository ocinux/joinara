<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** MVP-3 privacy gate: server-side MIME + size validation for guest photo upload. */
class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $owner = User::factory()->create();
        $this->event = $owner->events()->create([
            'name' => 'Pernikahan Sari & Bayu',
            'starts_at' => '2027-02-14T10:00',
        ]);
    }

    public function test_valid_image_is_accepted_and_stored_as_pending(): void
    {
        $response = $this->post('/u/'.$this->event->invitation_token.'/photos', [
            'photo' => UploadedFile::fake()->image('momen.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $media = Media::query()->sole();
        $this->assertSame('pending', $media->status);
        $this->assertSame($this->event->id, $media->event_id);
        Storage::disk('local')->assertExists($media->path);
    }

    public function test_wrong_mime_type_is_rejected_server_side(): void
    {
        $response = $this->post('/u/'.$this->event->invitation_token.'/photos', [
            'photo' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors([
            'photo' => 'Format file harus JPG, PNG, atau WebP.',
        ]);
        $this->assertSame(0, Media::count());
    }

    public function test_oversized_file_is_rejected(): void
    {
        // 26 000 KB fake > 25 MB (25 600 KB) limit — size check is server-side.
        $response = $this->post('/u/'.$this->event->invitation_token.'/photos', [
            'photo' => UploadedFile::fake()->image('besar.png')->size(26_000),
        ]);

        $response->assertSessionHasErrors([
            'photo' => 'Ukuran foto maksimal 25 MB.',
        ]);
        $this->assertSame(0, Media::count());
    }

    public function test_missing_file_is_rejected(): void
    {
        $response = $this->post('/u/'.$this->event->invitation_token.'/photos', []);

        $response->assertSessionHasErrors('photo');
        $this->assertSame(0, Media::count());
    }
}
