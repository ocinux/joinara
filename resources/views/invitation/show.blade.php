@extends('layouts.app')

@section('title', $event->name.' — Undangan')
@section('content')
    <div class="card invitation">
        <h1>Undangan</h1>
        <p>Kami dengan senang hati mengundang Anda ke acara:</p>
        <h2>{{ $event->name }}</h2>
        <p><strong>{{ $event->partner_1 }}{{ $event->partner_2 ? ' & '.$event->partner_2 : '' }}</strong></p>
        <p>{{ $event->starts_at->format('d M Y H:i') }}{{ $event->location ? ' — '.$event->location : '' }}</p>
    </div>

    @if (session('status'))<div class="card"><p class="status">{{ session('status') }}</p></div>@endif

    <div class="card">
        <h2>RSVP</h2>
        <form method="POST" action="{{ route('invitation.rsvp', $event->invitation_token) }}">
            @csrf
            <label class="label" for="rsvp-name">Nama</label>
            <input type="text" id="rsvp-name" name="name" required maxlength="255">
            @error('name')<p class="error">{{ $message }}</p>@enderror

            <label class="label" for="rsvp-status">Kehadiran</label>
            <select id="rsvp-status" name="status" required>
                <option value="attending">Hadir</option>
                <option value="declined">Tidak hadir</option>
                <option value="tentative">Masih ragu</option>
            </select>
            @error('status')<p class="error">{{ $message }}</p>@enderror

            <label class="label" for="rsvp-plus-ones">Jumlah pendamping</label>
            <input type="number" id="rsvp-plus-ones" name="plus_ones" min="0" max="10" value="0">
            @error('plus_ones')<p class="error">{{ $message }}</p>@enderror

            <label class="label" for="rsvp-message">Catatan (opsional)</label>
            <textarea id="rsvp-message" name="message" rows="3" maxlength="500"></textarea>
            @error('message')<p class="error">{{ $message }}</p>@enderror

            <button type="submit">Kirim RSVP</button>
        </form>
    </div>

    <div class="card">
        <h2>Bagikan momen</h2>
        <p>Foto Anda hanya dilihat pemilik acara sebelum disetujui.</p>
        <form method="POST" action="{{ route('invitation.photos', $event->invitation_token) }}" enctype="multipart/form-data">
            @csrf
            <label class="label" for="photo">Foto (JPG, PNG, atau WebP, maks 25 MB)</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
            @error('photo')<p class="error">{{ $message }}</p>@enderror
            <button type="submit">Unggah foto</button>
        </form>
    </div>

    <p class="footer"><a href="{{ route('privacy') }}">Privasi &amp; data Anda</a></p>
@endsection
