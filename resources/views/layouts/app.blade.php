<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Joinara')</title>
    <link rel="manifest" href="/manifest.webmanifest" />
    <link rel="icon" href="/icons/icon-192.png" />
    <link rel="stylesheet" href="/css/app.css" />
    <meta name="theme-color" content="#2563eb" />
    @stack('head')
</head>
<body>
<div class="navbar"><div class="container">
    <strong>@yield('nav-title', 'Joinara')</strong>
    @hasSection('navbar-right')<div>@yield('navbar-right')</div>@endif
</div></div>
<div class="container">
    @if (session('status'))<p class="status">{{ session('status') }}</p>@endif
    @yield('content')
    <p class="footer">Joinara MVP — undangan digital wedding-first.</p>
</div>
</body>
</html>
