@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@section('hide_chrome', true)
@section('content')
<div class="user-layout">
    <aside class="user-sidebar">
        <div class="user-sidebar-head">
            <div class="user-logo">Dashboard.</div>
            <button class="user-close" type="button" aria-label="Tutup menu">
                &times;
            </button>
        </div>
        <nav class="user-nav">
            <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Pengguna</a>
            <a href="{{ route('dashboard') }}#klaim">Status Klaim</a>
        </nav>
    </aside>

    <div class="user-backdrop" aria-hidden="true"></div>

    <section class="user-main">
        <div class="user-mobile-bar">
            <button class="user-menu-toggle" type="button" aria-label="Buka menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <span class="user-mobile-title">Menu</span>
        </div>
        @yield('user-content')
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const toggle = document.querySelector('.user-menu-toggle');
    const closeBtn = document.querySelector('.user-close');
    const backdrop = document.querySelector('.user-backdrop');
    const links = document.querySelectorAll('.user-nav a');

    const closeMenu = () => body.classList.remove('user-menu-open');
    const openMenu = () => body.classList.add('user-menu-open');

    toggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        openMenu();
    });

    closeBtn?.addEventListener('click', closeMenu);
    backdrop?.addEventListener('click', closeMenu);
    links.forEach((link) => link.addEventListener('click', closeMenu));
});
</script>
@endpush
