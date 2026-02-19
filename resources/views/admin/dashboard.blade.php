@extends('layout.admin')

@section('admin-content')
<div class="dashboard-header">
    <div class="dashboard-header__content">
        <p class="eyebrow">Manajemen Data</p>
        <h1 class="hero-title">Dashboard Admin</h1>
        <p class="hero-subtitle">Kelola barang temuan, laporan hilang, dan klaim pengguna dengan cepat.</p>
    </div>
    <div class="hero-stats hero-stats--grid">
        <div class="stat-chip stat-chip--solid">
            <span>Barang tersimpan</span>
            <strong>{{ $itemCount }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Klaim pending</span>
            <strong>{{ $openClaims }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Laporan terbuka</span>
            <strong>{{ $openReports }}</strong>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

<div class="quick-actions">
    <a class="qa-btn" href="{{ route('admin.items.create') }}">Tambah barang temuan</a>
    <a class="qa-btn" href="{{ route('reports.create') }}">Laporkan barang hilang</a>
    <a class="qa-btn ghost" href="{{ route('admin.claims.index') }}">Verifikasi klaim</a>
    <a class="qa-btn ghost" href="{{ route('admin.reports.index') }}">Lihat semua laporan</a>
</div>

<div class="admin-helper">
    <div class="helper-item">
        <span class="helper-label">Fokus Hari Ini</span>
        <strong>Verifikasi klaim pending agar pengguna cepat mendapat kepastian.</strong>
    </div>
    <div class="helper-item">
        <span class="helper-label">Cek Laporan</span>
        <strong>Update status laporan sesuai bukti terbaru.</strong>
    </div>
    <div class="helper-item">
        <span class="helper-label">Data Bersih</span>
        <strong>Lengkapi kategori barang agar mudah ditemukan.</strong>
    </div>
</div>

<div class="cards-grid cards-grid--balanced">
    <div class="card card-balanced">
        <div class="card-head">
            <span class="section-label">Manajemen Barang Temuan</span>
            <h2 class="admin-section-title">Barang temuan</h2>
        </div>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-balanced">
                    <thead>
                        <tr>
                            <th>Nama barang</th>
                            <th>Kategori</th>
                            <th>Tanggal ditemukan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->found_at->format('d/m/Y') }}</td>
                            <td>{{ $item->status_label }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-note">
                                Belum ada data barang temuan.
                                <a href="{{ route('admin.items.create') }}">Tambah barang pertama</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-balanced">
        <div class="card-head">
            <span class="section-label">Manajemen Laporan Hilang</span>
            <h2 class="admin-section-title">Laporan hilang</h2>
        </div>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-balanced">
                    <thead>
                        <tr>
                            <th>Pelapor</th>
                            <th>Barang</th>
                            <th>Kategori</th>
                            <th>Tanggal laporan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestReports as $report)
                        <tr>
                            <td>{{ $report->user->name }}</td>
                            <td>{{ $report->item_name }}</td>
                            <td>{{ optional($report->category)->name ?? '-' }}</td>
                            <td>{{ $report->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="status-badge {{ $report->status }}">{{ $report->status_label }}</span>
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions-inline">
                                    <a href="{{ route('admin.reports.edit', $report) }}" class="icon-btn" title="Edit laporan">
                                        <span class="icon-pencil"></span>
                                    </a>
                                    <form action="{{ route('admin.reports.destroy-admin', $report) }}" method="POST"
                                        onsubmit="return confirm('Hapus laporan ini?')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Hapus laporan">
                                            <span class="icon-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-note">
                                Belum ada laporan hilang.
                                <a href="{{ route('admin.reports.index') }}">Lihat semua laporan</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-balanced">
        <div class="card-head">
            <span class="section-label">Riwayat Klaim</span>
            <h2 class="admin-section-title">Klaim terbaru</h2>
        </div>
        <div class="table-shell">
            <div class="table-responsive">
                <table class="table table-balanced">
                    <thead>
                        <tr>
                            <th>Pengaju</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Diklaim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestClaims as $claim)
                        <tr>
                            <td>{{ $claim->user->name }}</td>
                            <td>{{ $claim->item->name }}</td>
                            <td><span class="status-badge {{ $claim->status }}">{{ $claim->status_label }}</span></td>
                            <td>{{ optional($claim->claimed_at)->format('d/m/Y') }}</td>
                            <td class="cell-actions">
                                <div class="table-actions-inline">
                                    <a href="{{ route('admin.claims.index') }}" class="icon-btn" title="Detail/verifikasi">
                                        <span class="icon-eye"></span>
                                    </a>
                                    <form action="{{ route('admin.claims.destroy-admin', $claim) }}" method="POST"
                                        onsubmit="return confirm('Hapus klaim ini?')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Hapus">
                                            <span class="icon-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-note">
                                Belum ada klaim.
                                <a href="{{ route('admin.claims.index') }}">Periksa status klaim</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
