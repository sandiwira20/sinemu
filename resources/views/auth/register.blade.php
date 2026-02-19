    @extends('layout.main')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    @section('hide_chrome', true)
    @section('body_class', 'auth-body--scroll')
    @section('content')
    @php
    $visualSlides = [
    asset('images/login-wallet.png'),
    asset('images/login-gadgets.png'),
    asset('images/login-stationery.png'),
    ];
    @endphp
    <section class="auth-page auth-page--modern" aria-label="Form register SiNemu">
        <div class="auth-visual-panel" data-auth-slideshow>
            <div class="auth-visual-panel__topbar">
                <a href="{{ route('login') }}" class="auth-visual-panel__back" aria-label="Kembali ke login">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                    <span>Kembali ke Login</span>
                </a>
                <div class="auth-visual-panel__logo">
                    <img src="{{ asset('images/logo-si.png') }}" class="logo-mark" alt="Logo SiNemu">
                    <span class="logo-name">Nemu</span>
                </div>
            </div>
            <div class="auth-visual-panel__headline">
                <p class="eyebrow">Selamat Datang</p>
                <h3>Buat akun baru.</h3>
                <p>Daftar sebagai pengguna untuk melaporkan dan mengklaim barang hilang.</p>
            </div>
            @foreach ($visualSlides as $slide)
            <article class="auth-visual-panel__slide{{ $loop->first ? ' is-active' : '' }}" data-auth-slide>
                <figure>
                    <img src="{{ $slide }}" alt="Ilustrasi SiNemu">
                </figure>
            </article>
            @endforeach
        </div>
        <div class="auth-panel">
            <div class="auth-panel__header">
                <p class="eyebrow">Daftar</p>
                <h2>Buat akun SiNemu</h2>
                <p>Daftar dengan email apa pun untuk mulai melaporkan dan mengklaim barang.</p>
            </div>
            <div class="auth-panel__body">
                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Periksa kembali data yang diisi.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ route('register') }}" class="auth-form-modern">
                    @csrf
                    <label class="form-lab">
                        <span>Nama Lengkap</span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </label>
                    <label class="form-lab">
                        <span>NIM</span>
                        <input id="nim" type="text" name="nim" value="{{ old('nim') }}">
                    </label>
                    <label class="form-lab">
                        <span>Email</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Masukkan email">
                    </label>
                    <label class="form-lab">
                        <span>Password</span>
                        <div class="password-field">
                            <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter">
                            <button type="button" class="password-toggle js-toggle-password" data-target="password"
                                aria-label="Tampilkan sandi">
                                <svg class="icon-eye-open" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="currentColor"
                                        d="M12 5c-4.5 0-8.4 2.6-10 7 1.6 4.4 5.5 7 10 7s8.4-2.6 10-7c-1.6-4.4-5.5-7-10-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z" />
                                </svg>
                                <svg class="icon-eye-off is-hidden" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="currentColor"
                                        d="M2.1 3.5 3.5 2.1l18.4 18.4-1.4 1.4-3.2-3.2c-1.6.9-3.4 1.3-5.3 1.3-4.5 0-8.4-2.6-10-7a12.6 12.6 0 0 1 3.9-5.3L2.1 3.5Zm7.2 7.2a3 3 0 0 0 4.2 4.2l-4.2-4.2ZM12 7a5 5 0 0 1 5 5c0 .6-.1 1.2-.3 1.8l-1.6-1.6a3 3 0 0 0-3.9-3.9L9.6 6.7C10.2 6.6 11.1 7 12 7Zm-6.6 2.1A9.8 9.8 0 0 0 4 12c1.4 3.7 4.7 6 8 6 1.2 0 2.4-.3 3.4-.8l-1.5-1.5a5 5 0 0 1-6.9-6.9l-1.6-1.7Z" />
                                </svg>
                            </button>
                        </div>
                    </label>
                    <label class="form-lab">
                        <span>Konfirmasi Password</span>
                        <div class="password-field">
                            <input id="password_confirmation" type="password" name="password_confirmation" required>
                            <button type="button" class="password-toggle js-toggle-password"
                                data-target="password_confirmation" aria-label="Tampilkan sandi">
                                <svg class="icon-eye-open" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="currentColor"
                                        d="M12 5c-4.5 0-8.4 2.6-10 7 1.6 4.4 5.5 7 10 7s8.4-2.6 10-7c-1.6-4.4-5.5-7-10-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z" />
                                </svg>
                                <svg class="icon-eye-off is-hidden" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="currentColor"
                                        d="M2.1 3.5 3.5 2.1l18.4 18.4-1.4 1.4-3.2-3.2c-1.6.9-3.4 1.3-5.3 1.3-4.5 0-8.4-2.6-10-7a12.6 12.6 0 0 1 3.9-5.3L2.1 3.5Zm7.2 7.2a3 3 0 0 0 4.2 4.2l-4.2-4.2ZM12 7a5 5 0 0 1 5 5c0 .6-.1 1.2-.3 1.8l-1.6-1.6a3 3 0 0 0-3.9-3.9L9.6 6.7C10.2 6.6 11.1 7 12 7Zm-6.6 2.1A9.8 9.8 0 0 0 4 12c1.4 3.7 4.7 6 8 6 1.2 0 2.4-.3 3.4-.8l-1.5-1.5a5 5 0 0 1-6.9-6.9l-1.6-1.7Z" />
                                </svg>
                            </button>
                        </div>
                    </label>
                    <button type="submit" class="btn-primary btn-rounded">Daftar Sekarang</button>
                </form>

                <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
            </div>
        </div>
    </section>
    @endsection

@push('scripts')
    <script src="{{ asset('js/password-toggle.js') }}"></script>
@endpush
