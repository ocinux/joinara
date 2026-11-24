<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /** Server-side upload rules (MVP-3): MIME by content + 25 MB per file. */
    private const PHOTO_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const PHOTO_MAX_KB = 25_600;

    private const RSVP_MESSAGES = [
        'name.required' => 'Nama wajib diisi.',
        'name.max' => 'Nama maksimal :max karakter.',
        'status.required' => 'Pilih status kehadiran.',
        'status.in' => 'Status kehadiran tidak valid.',
        'plus_ones.integer' => 'Jumlah pendamping harus berupa angka.',
        'plus_ones.between' => 'Jumlah pendamping harus antara :min dan :max.',
        'message.max' => 'Catatan maksimal :max karakter.',
    ];

    private const PHOTO_MESSAGES = [
        'photo.required' => 'Pilih foto untuk diunggah.',
        'photo.mimetypes' => 'Format file harus JPG, PNG, atau WebP.',
        'photo.max' => 'Ukuran foto maksimal 25 MB.',
        'photo.uploaded' => 'Unggahan gagal: file terlalu besar atau rusak.',
    ];

    /** Public invitation link — no login, reached via the random token. */
    public function show(string $token): View
    {
        $event = $this->findOr404($token);

        // Access log WITHOUT the token or guest PII (MVP-3: token never in logs).
        Log::info('invitation.view', ['event_id' => $event->id]);

        return view('invitation.show', ['event' => $event]);
    }

    /** Check-in landing target the QR points at (scan flow lands here in MVP check-in). */
    public function checkin(string $token): View
    {
        $event = $this->findOr404($token);

        Log::info('invitation.checkin', ['event_id' => $event->id]);

        return view('invitation.checkin', ['event' => $event]);
    }

    /** Public RSVP submit — no login. Guest matched by name, created on first RSVP. */
    public function rsvp(Request $request, string $token): RedirectResponse
    {
        $event = $this->findOr404($token);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:attending,declined,tentative'],
            'plus_ones' => ['nullable', 'integer', 'min:0', 'max:10'],
            'message' => ['nullable', 'string', 'max:500'],
        ], self::RSVP_MESSAGES);

        $guest = Guest::where('event_id', $event->id)
            ->whereRaw('lower(name) = ?', [mb_strtolower($data['name'])])
            ->first()
            ?? $event->guests()->create([
                'name' => $data['name'],
                'unique_code' => $this->uniqueGuestCode(),
            ]);

        $guest->rsvps()->create([
            'event_id' => $event->id,
            'status' => $data['status'],
            'plus_ones' => $data['plus_ones'] ?? 0,
            'message' => $data['message'] ?? null,
        ]);

        // No name/phone/message in the log — RSVP text is PII.
        Log::info('rsvp.submitted', ['event_id' => $event->id, 'status' => $data['status']]);

        return back()->with('status', 'Terima kasih! RSVP Anda sudah kami terima.');
    }

    /** Public photo upload — server-side MIME + size validation, stored as pending. */
    public function photos(Request $request, string $token): RedirectResponse
    {
        $event = $this->findOr404($token);

        $request->validate([
            'photo' => ['required', 'file', 'mimetypes:'.implode(',', self::PHOTO_MIMES), 'max:'.self::PHOTO_MAX_KB],
        ], self::PHOTO_MESSAGES);

        $file = $request->file('photo');
        $path = $file->storeAs(
            'media/event-'.$event->id,
            Str::uuid()->toString().'.'.strtolower($file->extension()),
            'local'
        );

        $event->media()->create(['path' => $path, 'status' => 'pending']);

        Log::info('photo.uploaded', ['event_id' => $event->id]);

        return back()->with('status', 'Foto terkirim dan menunggu persetujuan pemilik acara.');
    }

    private function findOr404(string $token): Event
    {
        // Explicit 404 with a readable message — never a 500 on a bad token.
        return Event::where('invitation_token', $token)->first()
            ?? abort(404, 'Tautan undangan tidak ditemukan atau sudah tidak berlaku.');
    }

    private function uniqueGuestCode(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $code = Str::random(12);
            if (! Guest::where('unique_code', $code)->exists()) {
                return $code;
            }
        }

        abort(500, 'Gagal membuat kode tamu.');
    }
}
