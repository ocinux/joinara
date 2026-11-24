@extends('layouts.app')

@section('title', $event->name.' — Undangan')
@section('content')
    <div class="card invitation">
        <h1>Undangan</h1>
        <p>Kami dengan senang hati mengundang Anda ke acara:</p>
        <h2>{{ $event->name }}</h2>
        <p><strong>{{ $event->partner_1 }}{{ $event->partner_2 ? ' & '.$event->partner_2 : '' }}</strong></p>
        <p>{{ $event->starts_at->format('d M Y H:i') }}{{ $event->location ? ' — '.$event->location : '' }}</p>
        <p class="footer">RSVP &amp; galeri foto akan segera hadir.</p>
    </div>
@endsection
