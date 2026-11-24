<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Undangan — Joinara')</title>
    <link rel="icon" href="/icons/icon-192.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Source+Sans+3:wght@400;600&display=swap" />
    <link rel="stylesheet" href="/css/app.css" />
    @stack('head')
</head>
<body class="invitation-page">
    <main class="invite" id="main">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
