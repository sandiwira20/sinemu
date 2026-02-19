@extends('layout.main')

@section('title', 'SiNemu - Barang Hilang & Temuan')
@section('meta_description', 'SiNemu memudahkan pelaporan barang hilang dan klaim barang temuan di kampus. Cari katalog, ajukan laporan, dan pantau status.')

@section('content')
<section class="hero-top">
    <div class="container hero-flex">
        <div class="hero-copy">
            <p class="eyebrow">Cari & Temukan</p>
            <h1>SiNemu, solusi cepat untuk barang hilang dan temuan di kampus.</h1>
            <p class="hero-subtitle">
                Telusuri kategori, lihat detail barang, dan ajukan laporan atau klaim dengan mudah.
            </p>
            @php
            $ctaRoute = route('login');
            if(auth()->check()) {
            $ctaRoute = optional(auth()->user()->role)->name === 'admin'
            ? route('admin.dashboard')
            : route('dashboard');
            }
            @endphp
            <div class="hero-actions">
                <a href="{{ $ctaRoute }}" class="btn cta-btn">Titip & Laporkan</a>
                <a href="{{ route('catalog') }}" class="btn btn-outline">Lihat Barang Temuan</a>
            </div>
            <div class="hero-meta">
                <div class="meta-card">
                    <strong>200+</strong>
                    <span>Barang berhasil kembali</span>
                </div>
                <div class="meta-card">
                    <strong>24/7</strong>
                    <span>Laporkan & klaim online</span>
                </div>
                <div class="meta-card muted">
                    <span class="dot"></span>
                    <span>Verifikasi admin terkelola</span>
                </div>
            </div>
            <div class="hero-chips">
                <span class="chip">Klaim cepat</span>
                <span class="chip">Pelacakan status</span>
                <span class="chip">Kategori lengkap</span>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-bubble"></div>
            <img src="{{ asset('images/gambar-home.png') }}" alt="Ilustrasi SiNemu"
                onerror='this.onerror=null; this.src="{{ asset('images/placeholder.png') }}";'>
        </div>
    </div>
</section>

<section class="section steps-section">
    <div class="container">
        <div class="steps-header">
            <p class="eyebrow">Langkah Cepat</p>
            <h3 class="section-title">Mulai temukan barang dalam 3 langkah.</h3>
            <p class="section-subtitle">Ringkas dan jelas untuk pengguna baru maupun lama.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <span class="step-number">01</span>
                <h4>Laporkan kehilangan</h4>
                <p>Isi detail barang, lokasi, dan waktu kehilangan agar admin bisa memproses cepat.</p>
            </div>
            <div class="step-card">
                <span class="step-number">02</span>
                <h4>Telusuri katalog</h4>
                <p>Cek barang temuan terbaru sesuai kategori agar proses klaim lebih akurat.</p>
            </div>
            <div class="step-card">
                <span class="step-number">03</span>
                <h4>Ajukan klaim</h4>
                <p>Kirim klaim lengkap dan pantau status verifikasi langsung dari dashboard.</p>
            </div>
        </div>
        <div class="steps-actions">
            <a href="{{ $ctaRoute }}" class="btn cta-btn">Mulai Sekarang</a>
            <a href="{{ route('catalog') }}" class="btn btn-outline">Jelajahi Barang Temuan</a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scrollKey = 'landingScrollY';
    const savedScroll = sessionStorage.getItem(scrollKey);
    if (savedScroll) {
        sessionStorage.removeItem(scrollKey);
        window.scrollTo({ top: Number(savedScroll) || 0, left: 0, behavior: 'auto' });
    }

    document.querySelectorAll('.catalog-carousel').forEach(carousel => {
        const track = carousel.querySelector('.catalog-track');
        const cards = Array.from(carousel.querySelectorAll('.catalog-card'));
        const prev = carousel.querySelector('.catalog-control-prev');
        const next = carousel.querySelector('.catalog-control-next');
        const wrapper = carousel.querySelector('.catalog-track-wrapper');
        let index = 0;
        let cardWidth = 0;
        let gap = 0;
        let perPage = 1;
        let maxIndex = 0;

        const update = () => {
            const shift = -(cardWidth + gap) * index;
            track.style.transform = `translateX(${shift}px)`;
            if (prev) prev.disabled = index === 0;
            if (next) next.disabled = index >= maxIndex;
        };

        const calc = () => {
            if (!cards.length) return;
            gap = parseFloat(getComputedStyle(track).gap || 0);
            cardWidth = cards[0].offsetWidth;
            perPage = Math.max(1, Math.floor((wrapper.clientWidth + gap) / (cardWidth + gap)));
            maxIndex = Math.max(0, cards.length - perPage);
            if (perPage >= cards.length) {
                index = 0;
            } else {
                index = Math.min(index, maxIndex);
            }
            update();
        };

        prev?.addEventListener('click', () => {
            index = Math.max(0, index - 1);
            update();
        });

        next?.addEventListener('click', () => {
            index = Math.min(maxIndex, index + 1);
            update();
        });

        carousel.addEventListener('wheel', (event) => {
            if (event.deltaY === 0 && event.deltaX === 0) return;
            event.preventDefault();
            if (event.deltaY > 0 || event.deltaX > 0) {
                index = Math.min(maxIndex, index + 1);
            } else {
                index = Math.max(0, index - 1);
            }
            update();
        }, { passive: false });

        window.addEventListener('resize', calc);
        calc();
    });

    document.querySelectorAll('.category-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            sessionStorage.setItem('landingScrollY', String(window.scrollY));
        });
    });
});
</script>
@endpush

<section class="section latest-section landing-catalog" id="kategori">
    <div class="container">
        <h3 class="section-title">Katalog Barang</h3>
        <p class="section-subtitle">Jelajahi barang terbaru berdasarkan kategori.</p>
        <div class="category-tabs">
            @foreach($categories as $cat)
            <a href="{{ route('landing', ['category' => $cat->slug]) }}"
                class="category-tab{{ $selected === $cat->slug ? ' is-active' : '' }}">
                {{ $cat->name }}
            </a>
            @endforeach
            @if($selected)
            <a href="{{ route('landing') }}" class="category-tab reset-tab">Semua</a>
            @endif
        </div>
    </div>

    <div class="container" id="landing-items" style="margin-top:1.5rem;">
        <div class="catalog-carousel">
            <button class="catalog-control catalog-control-prev" type="button" aria-label="Sebelumnya">&#10094;</button>
            <div class="catalog-track-wrapper">
                <div class="catalog-track">
                    @forelse($latestItems as $item)
                    @php
                    $imageUrl = $item->main_image ? asset('storage/'.$item->main_image) :
                    asset('images/placeholder.png');
                    @endphp
                    <article class="catalog-card">
                        <div class="catalog-image">
                            <img src="{{ $imageUrl }}" alt="{{ $item->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
                            <div class="catalog-chip">{{ optional($item->category)->name ?? 'Barang' }}</div>
                        </div>
                        <div class="catalog-body">
                            <h3 class="catalog-title">{{ $item->name }}</h3>
                            <p class="catalog-desc">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</p>
                            <div class="catalog-meta">
                                <span class="pill pill-secondary">{{ $item->found_at->format('d M Y') }}</span>
                                <span class="pill muted">{{ $item->status_label }}</span>
                            </div>
                            <div class="catalog-actions">
                                <a href="{{ route('items.public.show', $item) }}" class="btn btn-outline btn-sm">
                                    <span class="icon-eye"></span> Detail
                                </a>
                                @auth
                                @if(optional(auth()->user()->role)->name === 'admin')
                                <a href="{{ route('admin.items.edit', $item) }}" class="icon-btn" title="Edit">
                                    <span class="icon-pencil"></span>
                                </a>
                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Hapus barang ini?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn danger" title="Hapus">
                                        <span class="icon-trash"></span>
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="item-empty" style="margin:1rem 0;">Belum ada barang pada kategori ini.</div>
                    @endforelse
                </div>
            </div>
            <button class="catalog-control catalog-control-next" type="button" aria-label="Berikutnya">&#10095;</button>
        </div>
    </div>
</section>

<section class="section about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-lead">
                <div class="eyebrow">Tentang</div>
                <h3 class="section-title">SiNemu.</h3>
                <p class="section-subtitle">
                    Barangmu hilang di kampus? Tenang aja! SiNemu jadi solusi cepat, praktis, dan rapi untuk melacak,
                    mengklaim, dan mengelola barang yang ditemukan. Laporkan atau cari tanpa harus keliling kampus.
                </p>
                @php
                $reportLink = auth()->check()
                ? (optional(auth()->user()->role)->name === 'admin'
                ? route('admin.items.create')
                : route('reports.create'))
                : route('login');
                @endphp
                <div class="about-actions">
                    <a href="{{ route('landing') }}#kategori" class="btn">Mulai Cari Barang</a>
                    <a href="{{ $reportLink }}" class="btn btn-outline">Laporkan Barang</a>
                </div>
                <div class="about-meta">
                    <div class="meta-chip">
                        <span class="dot"></span>
                        Notifikasi klaim real-time
                    </div>
                    <div class="meta-chip">
                        <span class="dot"></span>
                        Data terorganisir per kategori
                    </div>
                </div>
            </div>
            <div class="about-highlight">
                <div class="highlight-card">
                    <div class="highlight-head">
                        <span class="pill">Mengapa SiNemu?</span>
                        <h4>Koneksi cepat antara penemu & pemilik</h4>
                        <p>Tulis laporan singkat, unggah foto, dan pantau statusnya. Semua tersimpan aman tanpa ribet.
                        </p>
                    </div>
                    <div class="stats-row">
                        <div class="stat">
                            <strong>3x</strong>
                            <span>Lebih cepat menemukan barang</span>
                        </div>
                        <div class="stat">
                            <strong>24/7</strong>
                            <span>Akses & pelaporan online</span>
                        </div>
                        <div class="stat">
                            <strong>+Kategori</strong>
                            <span>Elektronik, kuliah, kendaraan, & lainnya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
