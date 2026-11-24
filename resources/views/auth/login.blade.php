<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Joinara — Login Owner</title>
    <link rel="manifest" href="/manifest.webmanifest" />
    <link rel="icon" href="/icons/icon-192.png" />
    <link rel="stylesheet" href="/css/app.css" />
    <meta name="theme-color" content="#2563eb" />
</head>
<body>
<div class="navbar"><div class="container"><strong>Joinara</strong><span>Owner</span></div></div>
<div class="container">
    <h1>Masuk sebagai owner</h1>
    <div class="card">
        @if ($errors->any())
            <p class="error">Email/password salah atau akun belum dibuat. Buat owner dengan <code>php artisan joinara:owner</code>.</p>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label class="label">Email</label>
            <input type="email" name="email" required autofocus value="{{ old('email') }}" />
            <label class="label">Password</label>
            <input type="password" name="password" required />
            <button type="submit">Masuk</button>
        </form>
    </div>
    <p class="footer">Joinara MVP — undangan digital wedding-first.</p>
    <div class="demo-note card">
        <h2>Demo cepat</h2>
        <p>Untuk mencoba tanpa membuat akun baru, jalankan:</p>
        <pre><code>php artisan joinara:owner --demo</code></pre>
        <p>Kredensial demo akan tampil di terminal. Tidak ada kredensial default yang tersimpan di repo.</p>
    </div>
</div>
</body>
</html>
