@extends('layouts.app')

@section('title', 'Joinara — Undangan Digital')

@section('navbar-right')
    <a class="btn-nav" href="{{ route('login') }}">Masuk</a>
@endsection

@section('content')
<main class="landing">
    <section class="hero">
        <h1>Undangan digital untuk pernikahan Anda.<br />Selesai dalam hitungan menit.</h1>
        <p class="tagline">Undangan ber-tautan unik, RSVP, dan QR check-in. Satu aplikasi, di server Anda sendiri.</p>
        <a class="btn" href="{{ route('login') }}">Mulai buat undangan</a>
    </section>

    <section class="steps">
        <h2>Cara kerja</h2>
        <ol>
            <li>
                <span class="step-n">01</span>
                <div>
                    <h3>Buat event</h3>
                    <p>Nama pasangan, tanggal, lokasi. Satu menit.</p>
                </div>
            </li>
            <li>
                <span class="step-n">02</span>
                <div>
                    <h3>Bagikan tautannya</h3>
                    <p>Setiap undangan punya tautan unik dan QR. Cetak, kirim chat, tempel di meja.</p>
                </div>
            </li>
            <li>
                <span class="step-n">03</span>
                <div>
                    <h3>Tamu buka, tanpa app</h3>
                    <p>Link dibuka di browser apa pun. RSVP tercatat, check-in lewat QR di hari-H.</p>
                </div>
            </li>
            <li>
                <span class="step-n">04</span>
                <div>
                    <h3>Kelola dari satu tempat</h3>
                    <p>Guest list, status RSVP, dan check-in tampil di dashboard admin Anda.</p>
                </div>
            </li>
        </ol>
    </section>

    <footer class="landing-footer">
        <span>Joinara — undangan digital wedding-first.</span>
        @if (Route::has('privacy'))
            <a href="{{ route('privacy') }}">Privasi</a>
        @endif
    </footer>
</main>
<style>
.landing { padding-top: 1.5rem; }
.hero { text-align: center; padding: 2.5rem .5rem 2rem; }
.hero h1 { font-size: 1.75rem; line-height: 1.25; letter-spacing: -.02em; margin-bottom: .75rem; }
.tagline { color: #475569; margin-bottom: 1.5rem; }
.steps { max-width: 36rem; margin: 0 auto; padding: 0 .5rem 3rem; }
.steps h2 { font-size: 1.1rem; margin-bottom: .75rem; text-transform: uppercase; letter-spacing: .08em; color: #64748b; }
.steps ol { list-style: none; margin: 0; padding: 0; border-top: 1px solid #e2e8f0; }
.steps li { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
.step-n { color: #64748b; font-variant-numeric: tabular-nums; font-size: .85rem; min-width: 2ch; padding-top: .15rem; }
.steps h3 { font-size: 1rem; margin-bottom: .2rem; }
.steps p { color: #475569; margin: 0; font-size: .95rem; }
.landing-footer { display: flex; justify-content: space-between; gap: .5rem; padding: 1rem .5rem; border-top: 1px solid #e2e8f0; color: #64748b; font-size: .85rem; }
.landing-footer a { color: #64748b; text-decoration: underline; text-underline-offset: 3px; }
@media (prefers-reduced-motion: no-preference) { .hero { animation: up .3s ease-out; } @keyframes up { from { opacity: 0; transform: translateY(6px); } } }
</style>
@endsection
