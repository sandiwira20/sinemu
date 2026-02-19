@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('hide_chrome', true)
@section('content')
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-head">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo">Dashboard.</a>
            <button class="admin-close" type="button" aria-label="Tutup menu">
                &times;
            </button>
        </div>
        <nav class="admin-nav">
            <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('admin.items.index') }}" class="{{ request()->routeIs('admin.items.*') ? 'active' : '' }}">Barang temuan</a>
            <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Laporan barang hilang</a>
            <a href="{{ route('admin.claims.index') }}" class="{{ request()->routeIs('admin.claims.*') ? 'active' : '' }}">Status klaim</a>
        </nav>
    </aside>

    <div class="admin-backdrop" aria-hidden="true"></div>

    <section class="admin-main">
        <div class="admin-mobile-bar">
            <button class="admin-menu-toggle" type="button" aria-label="Buka menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <span class="admin-mobile-title">Menu</span>
        </div>
        @yield('admin-content')
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const toggle = document.querySelector('.admin-menu-toggle');
    const closeBtn = document.querySelector('.admin-close');
    const backdrop = document.querySelector('.admin-backdrop');
    const links = document.querySelectorAll('.admin-nav a');

    const closeMenu = () => body.classList.remove('admin-menu-open');
    const openMenu = () => body.classList.add('admin-menu-open');

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
