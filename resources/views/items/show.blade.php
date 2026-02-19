@extends('layout.main')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom:1rem;">
            <a href="{{ url()->previous() }}" class="btn btn-outline">Kembali</a>
        </div>
        <div class="item-detail">
            <div class="item-detail__image">
                @if($item->main_image)
                <img src="{{ asset('storage/'.$item->main_image) }}" alt="{{ $item->name }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
                @else
                <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}">
                @endif
        </div>
        <div class="item-detail__info">
            <p class="eyebrow">Detail barang</p>
            <h1 class="section-title" style="margin:0 0 0.4rem;">{{ $item->name }}</h1>
            <p class="item-subtitle" style="margin:0 0 1rem;">{{ $item->description }}</p>
            <p class="item-subtitle" style="margin:0 0 1.2rem;">
                Disimpan di: <strong>{{ $item->stored_location ?? '-' }}</strong> &bull;
                Kontak: <strong>{{ $item->contact ?? '-' }}</strong>
            </p>

            <div class="item-meta" style="margin-bottom:1rem;">
                <span class="pill">{{ $item->category->name }}</span>
                <span class="pill pill-secondary">{{ $item->found_at->format('d F Y') }}</span>
                <span class="pill pill-secondary">Lokasi: {{ $item->found_location }}</span>
            </div>

            @auth
            @php $role = optional(auth()->user()->role)->name; @endphp
            <div class="detail-actions">
                @if($role === 'user')
                    <a href="{{ route('claims.create', $item) }}" class="btn">Klaim barang</a>
                @endif
                @if($role === 'admin')
                    <a href="{{ route('admin.items.edit', $item) }}" class="icon-btn" title="Edit">
                        <span class="icon-pencil"></span>
                    </a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Hapus">
                            <span class="icon-trash"></span>
                        </button>
                    </form>
                @endif
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline">Login untuk klaim</a>
            @endauth
        </div>
    </div>
    </div>
</section>
@endsection
