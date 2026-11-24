<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /** Public invitation link — no login, reached via the random token. */
    public function show(string $token): View
    {
        return view('invitation.show', ['event' => $this->findOr404($token)]);
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
}
