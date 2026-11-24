<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    private const RULES = [
        'name' => ['required', 'string', 'max:255'],
        'starts_at' => ['required', 'date'],
        'location' => ['nullable', 'string', 'max:255'],
        'partner_1' => ['nullable', 'string', 'max:255'],
        'partner_2' => ['nullable', 'string', 'max:255'],
    ];

    public function index(Request $request): View
    {
        return view('events.index', [
            'events' => $request->user()->events()->orderBy('starts_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('events.create', ['event' => new Event()]);
    }

    public function store(Request $request): RedirectResponse
    {
        // ponytail: inline validation; extract a FormRequest only if rules get shared.
        $event = $request->user()->events()->create($request->validate(self::RULES));

        return redirect()->route('events.show', $event)->with('status', 'Event dibuat.');
    }

    public function show(Request $request, string $event): View
    {
        $event = $request->user()->events()->findOrFail($event);

        return view('events.show', [
            'event' => $event,
            'invitationUrl' => route('invitation.show', $event->invitation_token),
            'checkinUrl' => route('invitation.checkin', $event->invitation_token),
        ]);
    }

    public function edit(Request $request, string $event): View
    {
        return view('events.edit', ['event' => $request->user()->events()->findOrFail($event)]);
    }

    public function update(Request $request, string $event): RedirectResponse
    {
        $event = $request->user()->events()->findOrFail($event);
        $event->update($request->validate(self::RULES));

        return redirect()->route('events.show', $event)->with('status', 'Event diperbarui.');
    }

    public function destroy(Request $request, string $event): RedirectResponse
    {
        $request->user()->events()->findOrFail($event)->delete();

        return redirect()->route('events.index')->with('status', 'Event dihapus.');
    }
}
