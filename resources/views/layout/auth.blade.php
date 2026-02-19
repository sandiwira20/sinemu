<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SiNemu')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- WAJIB UNTUK FIREBASE FETCH --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS KHUSUS AUTH --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>

<body class="@yield('body_class')">
    @yield('content')

    @stack('scripts')
</body>

</html>