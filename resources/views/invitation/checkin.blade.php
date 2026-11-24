@extends('layouts.app')

@section('title', 'Check-in — Joinara')
@section('content')
    <div class="card">
        <h1>Check-in</h1>
        <p>Selamat datang di {{ $event->name }}.</p>
        <p><strong>{{ $event->partner_1 }}{{ $event->partner_2 ? ' & '.$event->partner_2 : '' }}</strong></p>
        <p>{{ $event->starts_at->format('d M Y H:i') }}</p>
        <p class="footer">Scan QR untuk tandai hadir (fitur check-in owner menyusul).</p>
        <p class="footer"><a href="{{ route('privacy') }}">Privasi &amp; data Anda</a></p>
    </div>
@endsection
