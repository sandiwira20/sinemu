@extends('layout.admin')

@section('admin-content')
@php
    $statusCounts = collect($items)->groupBy('status')->map->count();
@endphp

<div class="admin-hero hero-block">
    <div>
        <p class="eyebrow">Inventori temuan</p>
        <h1 class="section-title" style="margin:0;">Barang temuan</h1>
        <p style="margin:0.25rem 0 0;color:#4a5c7a;">Daftar barang yang sudah dicatat oleh admin.</p>
    </div>
    <div class="hero-stats">
        <div class="stat-chip">
            <span>Tersimpan</span>
            <strong>{{ $statusCounts['stored'] ?? 0 }}</strong>
        </div>
        <div class="stat-chip">
            <span>Diproses</span>
            <strong>{{ $statusCounts['processing'] ?? 0 }}</strong>
        </div>
        <div class="stat-chip">
            <span>Dikembalikan</span>
            <strong>{{ $statusCounts['returned'] ?? 0 }}</strong>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1.5rem;">
    <div class="table-header">
        <div>
            <p class="eyebrow" style="margin:0;">Daftar barang temuan</p>
            <p class="table-subtext">Cek detail barang, status penyimpanan, dan lanjutkan proses klaim.</p>
        </div>
        <a href="{{ route('admin.items.create') }}" class="btn-chip primary">+ Input barang baru</a>
    </div>

    <div class="table-responsive">
        <table class="table table-comfy">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Ditemukan</th>
                    <th>Status</th>
                    <th style="min-width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div class="cell-title">{{ $item->name }}</div>
                        @if($item->location ?? false)
                        <div class="cell-muted">Lokasi: {{ $item->location }}</div>
                        @endif
                    </td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->found_at->format('d M Y') }}</td>
                    <td>
                        <span class="status-pill status-{{ $item->status }}">
                            {{ $item->status_label }}
                        </span>
                    </td>
                    <td>
                        <div class="action-row">
                            <a href="{{ route('admin.items.show', $item) }}" class="btn-chip ghost">Detail</a>
                            <a href="{{ route('admin.items.edit', $item) }}" class="btn-chip primary">Kirim</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <p class="empty-title">Belum ada barang temuan</p>
                        <p class="empty-subtext">Tambah barang pertama untuk mulai mengelola inventori.</p>
                        <div class="action-row" style="margin-top:0.6rem;">
                            <a href="{{ route('admin.items.create') }}" class="btn-chip primary">+ Input barang baru</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-pagination">
        {{ $items->links() }}
    </div>
</div>
@endsection
