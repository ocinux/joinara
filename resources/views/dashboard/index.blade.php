<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Joinara — Dashboard</title>
    <link rel="manifest" href="/manifest.webmanifest" />
    <link rel="icon" href="/icons/icon-192.png" />
    <link rel="stylesheet" href="/css/app.css" />
    <meta name="theme-color" content="#2563eb" />
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(function(err){
                    // Service worker optional — don't break PWA shell if registration fails.
                });
            });
        }
    </script>
</head>
<body>
<div class="navbar"><div class="container">
    <strong>Joinara Dashboard</strong>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:rgba(255,255,255,.2);border:none;color:white;padding:.4rem .8rem;border-radius:.5rem;cursor:pointer;">Logout</button>
    </form>
</div></div>
<div class="container">
    <h1>Halo, {{ auth()->user()->name }}</h1>
    <div class="card">
        <p>Shell PWA Joinara. PWA assets: <a href="/manifest.webmanifest">manifest</a>, <a href="/css/app.css">CSS</a>, <a href="/sw.js">service worker</a>.</p>
        <p><a class="btn" href="{{ route('events.index') }}">Kelola event</a></p>
    </div>
    <p class="footer">Joinara MVP — tautan undangan ber-token &amp; QR check-in tersedia per event. RSVP, galeri, dan moderasi menyusul.</p>
</div>
</body>
</html>
