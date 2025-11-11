<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dinas Pariwisata Kota Bengkulu')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);
            font-family: 'Figtree', sans-serif;
        }
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="antialiased">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-white p-4">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
