<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SiNemu')</title>
    <meta name="description" content="@yield('meta_description', 'SiNemu membantu pelaporan barang hilang dan klaim barang temuan di kampus dengan cepat dan terorganisir.')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo-si.png') }}" type="image/png">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'SiNemu')))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', 'SiNemu membantu pelaporan barang hilang dan klaim barang temuan di kampus dengan cepat dan terorganisir.')))">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-si.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- 🔐 WAJIB untuk login Google / fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('styles')
</head>

<body class="@yield('body_class')">

    @hasSection('hide_chrome')
    @else
        @if(Route::currentRouteName() === 'landing')
            @include('components.navbar')
        @endif
    @endif

    <main>
        @yield('content')
    </main>

    @hasSection('hide_chrome')
    @else
        @if(Route::currentRouteName() === 'landing')
            @include('components.footer')
        @endif
    @endif

    {{-- JS global --}}
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/navbar.js') }}" defer></script>
    <script src="{{ asset('js/footer.js') }}" defer></script>

    {{-- JS khusus halaman (login google ada di sini) --}}
    @stack('scripts')

</body>
</html>
