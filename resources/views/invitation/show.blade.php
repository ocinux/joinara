@php
    /** @var \App\Models\Event $event */
    $pairing = trim(($event->partner_1 ?? '').(($event->partner_2 ?? '') ? ' & '.$event->partner_2 : ''));
    $date = $event->starts_at->locale('id')->translatedFormat('l, d F Y');
    $time = $event->starts_at->format('H.i');
@endphp

@extends('layouts.invitation')

@section('title', $event->name.' — Undangan')

@section('content')
    <header class="invite-hero">
        <p class="invite-eyebrow">Undangan Pernikahan</p>
        {{-- ponytail: foto pasangan akan ditambahkan saat MVP-7 selesai; sementara gunakan bingkai dekoratif --}}
        <div class="invite-ornament" aria-hidden="true"></div>
        <h1 id="names">{{ $pairing ?: $event->name }}</h1>
        <p class="invite-date">{{ $date }}</p>
    </header>

    <section class="invite-section invite-quote" aria-label="Salam">
        <h2 class="visually-hidden">Ucapan</h2>
        <p>Kami dengan penuh rasa syukur mengundang Anda untuk hadir dalam momen bahagia kami.</p>
    </section>

    <section class="invite-section" aria-labelledby="detail-heading">
        <h2 id="detail-heading">Detail Acara</h2>
        <dl class="invite-detail">
            <dt>Waktu</dt>
            <dd>{{ $time }} — Akad &amp; Resepsi</dd>
            @if ($event->location)
                <dt>Lokasi</dt>
                <dd>
                    {{ $event->location }}
                    <a class="invite-map"
                       href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($event->location) }}"
                       target="_blank" rel="noopener noreferrer">
                        Lihat di Peta
                    </a>
                </dd>
            @endif
        </dl>
    </section>

    <section class="invite-section" aria-labelledby="rsvp-heading">
        <h2 id="rsvp-heading">Konfirmasi Kehadiran</h2>
        <p class="helper">Mohon diisi selambatnya 1 minggu sebelum acara.</p>

        @if ($rsvp && $guest)
            {{-- State: guest already confirmed / just confirmed (PRG) --}}
            @if (session('rsvp_success'))
                <div class="panel-success" role="status" aria-live="polite">
                    <h2>Terima kasih, {{ $guest->name }}!</h2>
                    <p>Konfirmasi Anda sudah kami terima.</p>
                    <ul class="panel-summary">
                        <li>Status: {{ $rsvp->status === 'attending' ? 'Hadir' : 'Tidak hadir' }}</li>
                        @if ($rsvp->status === 'attending')
                            <li>Jumlah tamu: {{ $rsvp->plus_ones + 1 }}</li>
                        @endif
                        @if ($rsvp->message)
                            <li>Pesan: {{ $rsvp->message }}</li>
                        @endif
                    </ul>
                </div>
            @else
                <div class="panel-success" role="status" aria-live="polite">
                    <h2>Anda sudah konfirmasi kehadiran</h2>
                    <p>Makasih sudah konfirmasi! Ini ringkasan jawaban Anda:</p>
                    <ul class="panel-summary">
                        <li>Status: {{ $rsvp->status === 'attending' ? 'Hadir' : 'Tidak hadir' }}</li>
                        @if ($rsvp->status === 'attending')
                            <li>Jumlah tamu: {{ $rsvp->plus_ones + 1 }}</li>
                        @endif
                        @if ($rsvp->message)
                            <li>Pesan: {{ $rsvp->message }}</li>
                        @endif
                    </ul>
                </div>
            @endif
        @elseif ($guest)
            {{-- State: default form --}}
            @if ($errors->rsvp->any())
                <div class="alert-error" role="alert">
                    {{ $errors->rsvp->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.rsvp', $event->invitation_token) }}" id="rsvp-form">
                @csrf
                <input type="hidden" name="invitation_code" value="{{ $guest->unique_code }}">

                <div>
                    <label for="guest_name">Nama Anda</label>
                    <input type="text" name="guest_name" id="guest_name" required maxlength="100"
                           autocomplete="name"
                           value="{{ old('guest_name', $guest->name) }}"
                           @if ($errors->rsvp->has('guest_name')) aria-invalid="true" aria-describedby="guest_name-error" @endif
                    >
                    @if ($errors->rsvp->has('guest_name'))
                        <p class="field-error" id="guest_name-error" aria-live="polite">{{ $errors->rsvp->first('guest_name') }}</p>
                    @endif
                </div>

                <fieldset>
                    <legend>Apakah Anda hadir?</legend>
                    <div class="choice">
                        <input type="radio" name="attendance" id="attendance-hadir" value="hadir"
                               {{ old('attendance') === 'hadir' ? 'checked' : '' }}
                               @if ($errors->rsvp->has('attendance')) aria-invalid="true" aria-describedby="attendance-error" @endif>
                        <label for="attendance-hadir">Ya, insyaallah hadir</label>
                    </div>
                    <div class="choice">
                        <input type="radio" name="attendance" id="attendance-tidak" value="tidak"
                               {{ old('attendance') === 'tidak' ? 'checked' : '' }}
                               @if ($errors->rsvp->has('attendance')) aria-invalid="true" aria-describedby="attendance-error" @endif>
                        <label for="attendance-tidak">Mohon maaf, tidak bisa hadir</label>
                    </div>
                    @if ($errors->rsvp->has('attendance'))
                        <p class="field-error" id="attendance-error" aria-live="polite">{{ $errors->rsvp->first('attendance') }}</p>
                    @endif
                </fieldset>

                <div id="party-size-wrap" @if (old('attendance') !== 'hadir') hidden @endif>
                    <label for="party_size">Jumlah tamu (termasuk Anda)</label>
                    <input type="text" name="party_size" id="party_size" inputmode="numeric" pattern="[0-9]{1,2}"
                           value="{{ old('party_size') }}"
                           @if ($errors->rsvp->has('party_size')) aria-invalid="true" aria-describedby="party_size-error" @endif>
                    @if ($errors->rsvp->has('party_size'))
                        <p class="field-error" id="party_size-error" aria-live="polite">{{ $errors->rsvp->first('party_size') }}</p>
                    @endif
                </div>

                <div>
                    <label for="notes">Pesan untuk kami (opsional)</label>
                    <textarea name="notes" id="notes" maxlength="300">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn-submit" id="rsvp-submit">
                    <span class="btn-label">Kirim Konfirmasi</span>
                </button>
            </form>
        @else
            {{-- No personal code in link — read-only notice, form hidden entirely --}}
            <div class="alert-error" role="alert">
                <h2 class="visually-hidden" hidden>Tautan undangan tidak lengkap</h2>
                <p>Undangan ini bersifat personal. Silakan minta tautan undangan yang berisi kode dari pengundang Anda, lalu klik untuk mengisi konfirmasi kehadiran.</p>
            </div>
        @endif
    </section>

    <footer class="invite-footer">
        <p>Dibuat dengan ❤ oleh <a href="https://joinara.id">Joinara</a></p>
    </footer>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('rsvp-form');
            if (!form) return;

            const radios = form.querySelectorAll('input[name="attendance"]');
            const wrap = document.getElementById('party-size-wrap');
            const sizeInput = document.getElementById('party_size');

            const syncParty = () => {
                const hadir = [...radios].some(r => r.checked && r.value === 'hadir');
                wrap.hidden = !hadir;
                if (sizeInput) {
                    sizeInput.required = hadir;
                    if (hadir && !sizeInput.value) sizeInput.value = '1';
                }
            };

            // Spec: auto-hide party size when "tidak" is chosen.
            radios.forEach(r => r.addEventListener('change', syncParty));
            syncParty();

            // Spec: blur-triggered validation only after the field has been edited.
            const nameInput = document.getElementById('guest_name');
            nameInput?.addEventListener('blur', (e) => {
                if (e.target.dataset.touched) e.target.reportValidity();
                e.target.dataset.touched = '1';
            });

            form.addEventListener('submit', (e) => {
                // Cegah double-submit (MVP form, redirect setelah sukses).
                if (form.dataset.submitting === '1') { e.preventDefault(); return; }
                form.dataset.submitting = '1';
                form.classList.add('is-submitting');
                const btn = document.getElementById('rsvp-submit');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner" role="presentation"></span> Mengirim...';
            });
        })();
    </script>
@endpush
