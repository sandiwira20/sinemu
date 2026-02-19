@extends('layout.main')
@section('title', 'Login')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('hide_chrome', true)
@section('body_class', 'auth-body--locked')
@section('content')
    @php
        $visualSlides = [
            asset('images/login-wallet.png'),
            asset('images/login-gadgets.png'),
            asset('images/login-stationery.png'),
        ];
    @endphp
    <section class="auth-page auth-page--modern" aria-label="Form login SiNemu">
        <div class="auth-visual-panel" data-auth-slideshow>
            <div class="auth-visual-panel__topbar">
                <a href="{{ route('landing') }}" class="auth-visual-panel__back" aria-label="Kembali ke beranda">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                    <span>Kembali</span>
                </a>
                <div class="auth-visual-panel__logo">
                    <img src="{{ asset('images/logo-si.png') }}" class="logo-mark" alt="Logo SiNemu">
                    <span class="logo-name">Nemu</span>
                </div>
            </div>
            <div class="auth-visual-panel__headline">
                <p class="eyebrow">Selamat Datang</p>
                <h3>Selamat Datang.</h3>
                <p>Di aplikasi pencarian barang hilang di Politeknik Negeri Indramayu.</p>
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
                <p class="eyebrow">Masuk</p>
                <h2>Login ke SiNemu</h2>
                <p>Masuk dengan akun apa pun (kampus, Google, atau email lainnya) untuk mengelola laporan dan klaim.</p>
            </div>
            <div class="auth-panel__body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Email atau password yang kamu masukkan belum cocok.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form-modern">
                    @csrf
                    <label class="form-lab">
                        <span>Email</span>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                            placeholder="Masukkan email">
                    </label>
                    <label class="form-lab">
                        <span>Password</span>
                        <div class="password-field">
                            <input type="password" id="password" name="password" required placeholder="Masukkan password">
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
                    <div class="form-help">
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn-primary btn-rounded">Masuk Sekarang</button>
                </form>

                <div class="auth-divider auth-divider--muted"><span>atau</span></div>

                <div class="auth-oauth auth-oauth--outline">
                    <button type="button" class="btn-google" onclick="loginWithGoogle()">
                        <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path fill="#EA4335"
                                d="M12 10.2v3.84h5.38c-.23 1.24-.93 2.29-1.98 2.98l3.2 2.48c1.87-1.73 2.95-4.28 2.95-7.39 0-.71-.06-1.39-.17-2.05H12z" />
                            <path fill="#34A853"
                                d="M5.62 14.33l-.9.69-2.52 1.96C3.97 19.88 7.69 21.9 12 21.9c2.7 0 4.97-.89 6.63-2.4l-3.2-2.48c-.89.6-2.03.96-3.43.96-2.63 0-4.86-1.77-5.65-4.21z" />
                            <path fill="#4A90E2"
                                d="M3.1 7.02C2.4 8.42 2 9.95 2 11.5c0 1.55.4 3.08 1.1 4.48l3.52-2.77c-.2-.6-.32-1.24-.32-1.91 0-.66.12-1.3.32-1.9z" />
                            <path fill="#FBBC05"
                                d="M12 4.6c1.47 0 2.78.5 3.82 1.47l2.86-2.86C16.96 1.76 14.7.9 12 .9 7.69.9 3.97 2.92 2.2 5.96l3.52 2.77C7.14 6.37 9.37 4.6 12 4.6z" />
                        </svg>
                        Login dengan Google
                    </button>
                </div>


                <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = Array.from(document.querySelectorAll('[data-auth-slide]'));
            if (!slides.length) return;

            let idx = 0;
            setInterval(() => {
                slides[idx].classList.remove('is-active');
                idx = (idx + 1) % slides.length;
                slides[idx].classList.add('is-active');
            }, 4000);
        });
    </script>

    <script src="{{ asset('js/password-toggle.js') }}"></script>
    <script type="module" src="{{ asset('js/firebase-auth.js') }}"></script>
@endpush
