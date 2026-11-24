<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /** Public invitation page reached via event token (optional personal code in ?code=). */
    public function show(Request $request, string $token): View
    {
        $event = $this->findOr404($token);
        $guest = $this->resolveGuest($request, $event);
        $rsvp = $guest?->rsvps()->first();

        return view('invitation.show', [
            'event' => $event,
            'guest' => $guest,
            'rsvp' => $rsvp,
            'success' => session('rsvp_success'),
        ]);
    }

    /** RSVP submission. PRG (redirect after success) so a reload never double-posts. */
    public function rsvp(Request $request, string $token): RedirectResponse
    {
        $event = $this->findOr404($token);
        $guest = $this->resolveGuest($request, $event);

        if (! $guest) {
            // Spec: submit tanpa kode personal tidak diizinkan.
            return redirect()
                ->route('invitation.show', $event->invitation_token)
                ->withErrors(
                    ['invitation_code' => 'Undangan ini bersifat personal. Mohon gunakan tautan undangan yang dikirim oleh pengundang.'],
                    'rsvp'
                );
        }

        $data = $request->validateWithBag('rsvp', [
            'guest_name' => ['required', 'string', 'max:100'],
            'attendance' => ['required', 'in:hadir,tidak'],
            'party_size' => ['nullable', 'required_if:attendance,hadir', 'integer', 'min:1', 'max:10'],
            'notes' => ['nullable', 'string', 'max:300'],
            'invitation_code' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($guest, $event, $data) {
            // Self-reported name wins; dedup tetap via unique_code, nama hanya label.
            $guest->update(['name' => trim($data['guest_name'])]);

            Rsvp::updateOrCreate(
                ['guest_id' => $guest->id],
                [
                    'event_id' => $event->id,
                    'status' => $data['attendance'] === 'hadir' ? 'attending' : 'declined',
                    'plus_ones' => $data['attendance'] === 'hadir' ? max(0, ((int) $data['party_size']) - 1) : 0,
                    'message' => trim($data['notes'] ?? '') ?: null,
                ]
            );
        });

        return redirect()
            ->to(route('invitation.show', $event->invitation_token).'?code='.$guest->unique_code)
            ->with('rsvp_success', true);
    }

    /** Check-in landing target the QR points at (scan flow lands here in MVP check-in). */
    public function checkin(string $token): View
    {
        return view('invitation.checkin', ['event' => $this->findOr404($token)]);
    }

    private function findOr404(string $token): Event
    {
        // Explicit 404 with a readable message — never a 500 on a bad token.
        return Event::where('invitation_token', $token)->first()
            ?? abort(404, 'Tautan undangan tidak ditemukan atau sudah tidak berlaku.');
    }

    /**
     * Resolve a Guest from the optional `?code=` query param (guests.unique_code, scoped to the event).
     * ponytail: host-side personal-link distribution lands with MVP-5 guest list; codes currently
     * exist on guests created by owner tooling/seeds/tests.
     */
    private function resolveGuest(Request $request, Event $event): ?Guest
    {
        // GET pakai ?code=, POST pakai hidden invitation_code (spec form) — keduanya sumber sama.
        $code = $request->input('invitation_code') ?: $request->query('code');

        return $code
            ? Guest::where('unique_code', $code)->where('event_id', $event->id)->first()
            : null;
    }
}
