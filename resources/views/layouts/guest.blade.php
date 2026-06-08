<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WorkInsight')</title>
    @vite('resources/js/tabler.js')
    <style>
        :root { --tblr-primary: #f97316; --tblr-primary-rgb: 249, 115, 22; }
        body { background-color: #f6f7f9; }
        .navbar-brand-text { font-size: 1.5rem; font-weight: 700; letter-spacing: -.5px; color: #1a2434; }
        .navbar-brand-text span { color: #f97316; }
    </style>
</head>
<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand-text">Work<span>Insight</span></a>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>
