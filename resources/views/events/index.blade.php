@extends('layouts.app')

@section('title', 'Event — Joinara')
@section('navbar-right')
    <form method="POST" action="{{ route('logout') }}">@csrf
        <button type="submit" class="btn-nav">Logout</button>
    </form>
@endsection

@section('content')
    <h1>Event saya</h1>
    <p><a class="btn" href="{{ route('events.create') }}">+ Buat event</a></p>

    @if ($events->isEmpty())
        <div class="card"><p>Belum ada event. Buat event pertama untuk mendapatkan tautan undangan ber-token.</p></div>
    @else
        <table class="table">
            <thead><tr><th>Nama</th><th>Tanggal</th><th>Lokasi</th><th></th></tr></thead>
            <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->starts_at->format('d M Y H:i') }}</td>
                    <td>{{ $event->location ?? '—' }}</td>
                    <td><a href="{{ route('events.show', $event) }}">Kelola</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
