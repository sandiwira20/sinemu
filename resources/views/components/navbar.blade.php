<header class="navbar">
    <div class="container navbar-inner">
        <div class="navbar-left">
            <a href="{{ route('landing') }}" class="navbar-brand">
                <div class="navbar-logo">
                    <img src="{{ asset('images/logo-si.png') }}" alt="Logo SiNemu">
                </div>
                <span>Nemu</span>
            </a>
        </div>

        <form class="navbar-search" action="{{ route('catalog') }}" method="GET">
            <svg aria-hidden="true" viewBox="0 0 24 24" class="navbar-search-icon">
                <path fill="currentColor"
                    d="M15.5 14h-.79l-.28-.27a6.471 6.471 0 0 0 1.48-4.23 6.5 6.5 0 1 0-6.5 6.5 6.471 6.471 0 0 0 4.23-1.48l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang"
                aria-label="Cari barang">
            <button type="submit" class="navbar-search-btn" aria-label="Cari">
                <svg aria-hidden="true" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M15.5 14h-.79l-.28-.27a6.471 6.471 0 0 0 1.48-4.23 6.5 6.5 0 1 0-6.5 6.5 6.471 6.471 0 0 0 4.23-1.48l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                </svg>
            </button>
        </form>

        <div class="navbar-user">
            @auth
            @php
            $avatar = Auth::user()->avatar ?? null;
            $initial = strtoupper(substr(Auth::user()->name ?? 'U', 0, 1));
            $isAdmin = optional(Auth::user()->role)->name === 'admin';
            $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('dashboard');
            @endphp
            <div class="user-dropdown">
                <button class="user-trigger" type="button">
                    <div class="navbar-avatar">
                        @if($avatar)
                        <img src="{{ asset($avatar) }}" alt="Avatar">
                        @else
                        <div class="avatar-fallback">{{ $initial }}</div>
                        @endif
                    </div>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div class="user-menu">
                    <a href="{{ $dashboardRoute }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
            @endauth
        </div>

        <button class="navbar-toggle" type="button" aria-expanded="false" aria-controls="navbar-menu">
            <span class="navbar-toggle-bar"></span>
            <span class="navbar-toggle-bar"></span>
            <span class="navbar-toggle-bar"></span>
        </button>
    </div>

    <nav class="navbar-menu container" id="navbar-menu">
        <a href="{{ route('catalog', ['category' => 'barang-pribadi']) }}">Barang Pribadi</a>
        <a href="{{ route('catalog', ['category' => 'barang-elektronik']) }}">Barang Elektronik</a>
        <a href="{{ route('catalog', ['category' => 'barang-kuliah']) }}">Barang Kuliah</a>
        <a href="{{ route('catalog', ['category' => 'barang-kendaraan']) }}">Barang Kendaraan</a>
        <a href="{{ route('catalog', ['category' => 'barang-berharga']) }}">Barang Berharga</a>
    </nav>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.user-dropdown');
    if (dropdown) {
        const trigger = dropdown.querySelector('.user-trigger');
        const menu = dropdown.querySelector('.user-menu');
        const close = () => dropdown.classList.remove('is-open');
        const toggle = () => dropdown.classList.toggle('is-open');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggle();
        });

        menu.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', close);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') close();
        });
    }

    const navbar = document.querySelector('.navbar');
    const toggle = document.querySelector('.navbar-toggle');
    if (navbar && toggle) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = navbar.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', () => {
            if (navbar.classList.contains('is-open')) {
                navbar.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 900 && navbar.classList.contains('is-open')) {
                navbar.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>
@endpush
