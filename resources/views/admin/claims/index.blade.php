@extends('layout.admin')

@section('admin-content')
@php
    $pendingCount = $claims->where('status', 'pending')->count();
    $approvedCount = $claims->where('status', 'approved')->count();
    $rejectedCount = $claims->where('status', 'rejected')->count();
@endphp

<div class="admin-hero hero-block">
    <div>
        <p class="eyebrow">Verifikasi klaim</p>
        <h1 class="section-title" style="margin:0;">Klaim barang</h1>
        <p style="margin:0.25rem 0 0;color:#4a5c7a;">Validasi klaim pengguna dan setujui pengambilan barang.</p>
    </div>
    <div class="hero-stats">
        <div class="stat-chip">
            <span>Menunggu</span>
            <strong>{{ $pendingCount }}</strong>
        </div>
        <div class="stat-chip">
            <span>Disetujui</span>
            <strong>{{ $approvedCount }}</strong>
        </div>
        <div class="stat-chip">
            <span>Ditolak</span>
            <strong>{{ $rejectedCount }}</strong>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-header">
        <div>
            <p class="eyebrow" style="margin:0;">Daftar klaim</p>
            <p class="table-subtext">Lihat detail, hubungi pengaju, dan tentukan hasil klaim.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-comfy table-claims">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Pengaju</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Diajukan</th>
                    <th style="min-width:200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                <tr>
                    <td>
                        <div class="cell-title">{{ $claim->item->name }}</div>
                        <div class="cell-muted">ID barang: {{ $claim->item->id }}</div>
                    </td>
                    <td>
                        <div class="cell-title">{{ $claim->user->name }}</div>
                        <div class="cell-muted">{{ $claim->user->email }}</div>
                    </td>
                    <td>{{ $claim->contact }}</td>
                    <td>
                        <span class="status-pill status-{{ $claim->status }}">
                            {{ $claim->status_label }}
                        </span>
                    </td>
                    <td>{{ $claim->claimed_at->format('d M Y, H:i') }}</td>
                    <td>
                        <div class="action-row">
                            <a href="{{ route('admin.claims.show', $claim) }}" class="btn-chip ghost sm">
                                <span class="icon-18 icon-eye"></span>
                                Detail
                            </a>
                            @if($claim->status === 'pending')
                            <form action="{{ route('admin.claims.verify', $claim) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn-chip primary sm">Setujui</button>
                            </form>
                            <form action="{{ route('admin.claims.verify', $claim) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn-chip danger sm">Tolak</button>
                            </form>
                            @else
                            <span class="cell-muted">Sudah diproses</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <p class="empty-title">Belum ada klaim</p>
                        <p class="empty-subtext">Tunggu pengguna mengajukan klaim untuk diverifikasi.</p>
                        <div class="action-row" style="margin-top:0.6rem;">
                            <a href="{{ route('admin.dashboard') }}" class="btn-chip ghost">Kembali ke dashboard</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-pagination">
        {{ $claims->links() }}
    </div>
</div>
@endsection
