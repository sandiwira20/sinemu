@extends('layout.main')

@section('title', 'Katalog Barang - SiNemu')
@section('meta_description', 'Jelajahi katalog barang temuan terbaru, filter berdasarkan kategori, dan ajukan klaim dengan mudah.')

@section('content')
<section class="section latest-section catalog-page">
    <div class="container">
        <div class="section-header">
            <a class="icon-btn" href="{{ route('landing') }}" title="Kembali ke beranda" style="margin-right:0.6rem;">
                <span class="icon-arrow-left"></span>
            </a>
            <h3 class="section-title" style="margin:0;">Katalog Barang</h3>
        </div>
        <form class="catalog-search" method="GET" action="{{ route('catalog') }}">
            <input type="text" name="q" placeholder="Cari nama barang atau deskripsi..."
                value="{{ $query ?? '' }}" aria-label="Cari barang di katalog">
            @if($selected)
                <input type="hidden" name="category" value="{{ $selected }}">
            @endif
            <button type="submit" class="btn btn-outline">Cari</button>
            @if($query)
                <a href="{{ route('catalog', ['category' => $selected]) }}" class="btn btn-outline">Reset</a>
            @endif
        </form>
        <div class="category-tabs">
            @foreach($categories as $cat)
            <a href="{{ route('catalog', ['category' => $cat->slug]) }}"
                class="category-tab{{ $selected === $cat->slug ? ' is-active' : '' }}">
                {{ $cat->name }}
            </a>
            @endforeach
            @if($selected)
            <a href="{{ route('catalog') }}" class="category-tab reset-tab">Semua</a>
            @endif
        </div>
    </div>

    <div class="container" id="catalog-items" style="margin-top:1.5rem;">
        <div class="catalog-carousel">
            <button class="catalog-control catalog-control-prev" type="button" aria-label="Sebelumnya">&#10094;</button>
            <div class="catalog-track-wrapper">
                <div class="catalog-track">
        @forelse($items as $item)
        @php
            $imageUrl = $item->main_image ? asset('storage/'.$item->main_image) : asset('images/placeholder.png');
        @endphp
        <article class="catalog-card">
            <div class="catalog-image">
                <img src="{{ $imageUrl }}" alt="{{ $item->name }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
                <div class="catalog-chip">{{ $item->category->name }}</div>
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
                        <span class="icon-eye"></span>
                        Detail
                    </a>
                    @auth
                    @if(optional(auth()->user()->role)->name === 'admin')
                    <a href="{{ route('admin.items.edit', $item) }}" class="icon-btn" title="Edit">
                        <span class="icon-pencil"></span>
                    </a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')" style="margin:0;">
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
        @endforelse
                </div>
            </div>
            <button class="catalog-control catalog-control-next" type="button" aria-label="Berikutnya">&#10095;</button>
        </div>
        @if($items->isEmpty())
        <div class="item-empty" style="margin-top:1rem;">
            <strong>Belum ada barang pada filter ini.</strong>
            <p>Coba kata kunci lain, pilih kategori berbeda, atau laporkan barang hilangmu.</p>
            <div style="margin-top:0.6rem;display:flex;gap:0.6rem;flex-wrap:wrap;">
                <a href="{{ route('catalog') }}" class="btn btn-outline">Lihat semua</a>
                <a href="{{ auth()->check() ? route('reports.create') : route('login') }}" class="btn">
                    Laporkan barang
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="container" style="margin-top:1.5rem;">
        {{ $items->links() }}
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.catalog-carousel');
    const scrollKey = 'catalogScrollY';
    const savedScroll = sessionStorage.getItem(scrollKey);
    if (savedScroll) {
        sessionStorage.removeItem(scrollKey);
        window.scrollTo({ top: Number(savedScroll) || 0, left: 0, behavior: 'auto' });
    }
    if (!carousel) return;
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

    function calc() {
        if (!cards.length) return;
        gap = parseFloat(getComputedStyle(track).gap || 0);
        cardWidth = cards[0].offsetWidth;
        perPage = Math.max(1, Math.floor((wrapper.clientWidth) / (cardWidth + gap)));
        maxIndex = Math.max(0, cards.length - perPage);
        update();
    }

    function update() {
        const shift = -(cardWidth + gap) * index;
        track.style.transform = `translateX(${shift}px)`;
        prev.disabled = index === 0;
        next.disabled = index >= maxIndex;
    }

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

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.category-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            sessionStorage.setItem('catalogScrollY', String(window.scrollY));
        });
    });
});
</script>
@endpush
