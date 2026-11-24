@extends('layouts.app')

@section('title', $event->name.' — Joinara')
@section('content')
    <h1>{{ $event->name }}</h1>
    <div class="card">
        <p><strong>Pasangan:</strong> {{ $event->partner_1 ?? '—' }} {{ $event->partner_2 ? '&amp; '.$event->partner_2 : '' }}</p>
        <p><strong>Tanggal:</strong> {{ $event->starts_at->format('d M Y H:i') }}</p>
        <p><strong>Lokasi:</strong> {{ $event->location ?? '—' }}</p>

        <h2>Tautan undangan</h2>
        <p class="token">{{ $invitationUrl }}</p>
        <p><a href="{{ $invitationUrl }}" target="_blank" rel="noopener">Buka halaman undangan</a>
           · <a href="{{ route('events.edit', $event) }}">Ubah event</a></p>

        <h2>QR check-in</h2>
        <p>Tampilkan/scan tautan ini untuk check-in tamu.</p>
        <div class="qr">{{ \App\Support\Qr::svg($checkinUrl) }}</div>
        <p><small>Mengarah ke: <code>{{ $checkinUrl }}</code></small></p>
    </div>
    <form method="POST" action="{{ route('events.destroy', $event) }}"
          onsubmit="return confirm('Hapus event ini? Semua data terkait ikut terhapus.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger">Hapus event</button>
    </form>
@endsection
