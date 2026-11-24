@extends('layouts.app')

@section('title', 'Joinara — Undangan Digital')

@section('navbar-right')
    <a class="btn-nav" href="{{ route('login') }}">Masuk owner</a>
@endsection

@section('content')
<div class="landing">
    <div class="hero">
        <h1>Undangan digital untuk momen paling berharga.</h1>
        <p class="tagline">Wedding-first. Undangan, RSVP, dan QR check-in dalam satu aplikasi yang bisa Anda kendalikan sendiri.</p>
        <div class="cta-group">
            <a class="btn" href="{{ route('login') }}">Buka admin panel</a>
            <a class="btn btn-ghost" href="#fitur">Pelajari fitur</a>
        </div>
    </div>

    <div id="fitur" class="features">
        <div class="card">
            <h2>Undangan ber-token</h2>
            <p>Tamu buka link unik tanpa perlu login. Token unguessable; token tidak valid mengembalikan 404.</p>
        </div>
        <div class="card">
            <h2>RSVP &amp; guest list</h2>
            <p>RSVP otomatis tercatat ke guest list. Owner mengelola daftar tamu dan mengekspor CSV.</p>
        </div>
        <div class="card">
            <h2>QR check-in</h2>
            <p>Check-in di hari-H lewat QR undangan, dengan fallback manual. Tanpa layanan pihak ketiga.</p>
        </div>
        <div class="card">
            <h2>Data milik Anda</h2>
            <p>Semua tersimpan di SQLite lokal. Self-hosted, tanpa biaya langganan, tanpa iklan.</p>
        </div>
    </div>

    <div class="card demo-box">
        <h2>Mencoba demo</h2>
        <p>Panel admin ada di <code>/admin</code>. Buat akun owner lewat terminal:</p>
        <pre><code>php artisan joinara:owner</code></pre>
        <p>Kredensial muncul di terminal. Lalu masuk lewat <a href="{{ route('login') }}">halaman login</a>.</p>
    </div>
</div>
<style>
.landing { padding-top: 1.5rem; }
.hero { text-align: center; padding: 2.5rem .5rem 1.5rem; }
.hero h1 { font-size: 1.6rem; line-height: 1.3; margin-bottom: .75rem; }
.tagline { color: #475569; margin-bottom: 1.25rem; }
.cta-group { display: flex; gap: .5rem; justify-content: center; flex-wrap: wrap; }
.btn-ghost { background: #e2e8f0; color: #0f172a; }
.features { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: .75rem; }
.features h2 { font-size: 1rem; margin-bottom: .35rem; }
.demo-box code { background: #f1f5f9; padding: .15rem .35rem; border-radius: .3rem; }
.demo-box pre code { white-space: pre-wrap; font-size: .8rem; }
.demo-box pre { background: #0f172a; color: #e2e8f0; padding: .75rem; border-radius: .5rem; overflow-x: auto; margin: .5rem 0 1rem; }
@media (prefers-reduced-motion: no-preference) { .hero { animation: up .3s ease-out; } @keyframes up { from { opacity: 0; transform: translateY(6px);} } }
</style>
@endsection
