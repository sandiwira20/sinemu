@extends('layout.user')

@section('user-content')
<div class="dashboard-header">
    <div class="dashboard-header__content">
        <p class="eyebrow">Ringkasan</p>
        <h1 class="hero-title">Dashboard Pengguna</h1>
        <p class="hero-subtitle">Kelola laporan hilang yang kamu buat serta status klaim barang temuan.</p>
    </div>
    <div class="hero-stats hero-stats--grid">
        <div class="stat-chip stat-chip--solid">
            <span>Total laporan</span>
            <strong>{{ $reportStats['total'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Laporan open</span>
            <strong>{{ $reportStats['open'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Laporan matched</span>
            <strong>{{ $reportStats['matched'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Laporan closed</span>
            <strong>{{ $reportStats['closed'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Total klaim</span>
            <strong>{{ $claimStats['total'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Klaim pending</span>
            <strong>{{ $claimStats['pending'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Klaim disetujui</span>
            <strong>{{ $claimStats['approved'] }}</strong>
        </div>
        <div class="stat-chip stat-chip--solid">
            <span>Klaim ditolak</span>
            <strong>{{ $claimStats['rejected'] }}</strong>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

<div class="card helper-card">
    <div class="card-head">
        <span class="section-label">Langkah Cepat</span>
        <h2 class="admin-section-title">Mulai dari sini</h2>
        <p class="table-subtext">Tiga langkah singkat agar laporanmu cepat diproses.</p>
    </div>
    <div class="helper-steps">
        <div class="helper-step">
            <strong>1.</strong> Buat laporan kehilangan dengan detail lokasi & waktu.
        </div>
        <div class="helper-step">
            <strong>2.</strong> Pantau status laporan dan klaim lewat dashboard.
        </div>
        <div class="helper-step">
            <strong>3.</strong> Ajukan klaim saat barang temuan cocok dengan laporanmu.
        </div>
    </div>
    <div class="helper-actions">
        <a class="qa-btn" href="{{ route('reports.create') }}">Buat laporan</a>
        <a class="qa-btn ghost" href="{{ route('catalog') }}">Cari barang temuan</a>
    </div>
</div>

<div class="quick-actions">
    <a class="qa-btn" href="{{ route('reports.create') }}">Buat laporan baru</a>
    <a class="qa-btn" href="{{ route('catalog') }}">Cari barang temuan</a>
    <a class="qa-btn ghost" href="{{ route('dashboard') }}#klaim">Status klaim</a>
    <a class="qa-btn ghost" href="{{ route('reports.index') }}">Lihat laporan saya</a>
</div>

<div class="cards-grid cards-grid--balanced">
    <div class="card card-balanced">
        <div class="card-head">
            <span class="section-label">Laporan Barang Hilang</span>
            <h2 class="admin-section-title">Laporan saya</h2>
            <p class="table-subtext">Daftar laporan hilang yang pernah kamu kirim.</p>
        </div>
        <div class="table-shell user-table-scroll">
            <div class="table-responsive">
                <table class="table table-balanced">
                    <thead>
                        <tr>
                            <th>Nama barang</th>
                            <th>Lokasi hilang</th>
                            <th>Waktu hilang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->item_name }}</td>
                            <td>{{ $report->lost_location }}</td>
                            <td>{{ optional($report->lost_at)->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $report->status }}">{{ $report->status_label }}</span>
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions-inline">
                                    <a href="{{ route('reports.edit', $report) }}" class="icon-btn" title="Edit laporan">
                                        <span class="icon-pencil"></span>
                                    </a>
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')" style="margin:0;">
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
                                Belum ada laporan yang kamu kirim.
                                <a href="{{ route('reports.create') }}">Buat laporan pertama</a>
                                atau
                                <a href="{{ route('catalog') }}">cek barang temuan</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-balanced" id="klaim">
        <div class="card-head">
            <span class="section-label">Klaim Barang Temuan</span>
            <h2 class="admin-section-title">Klaim saya</h2>
            <p class="table-subtext">Pantau status klaim barang yang sudah diajukan.</p>
        </div>
        <div class="table-shell user-table-scroll">
            <div class="table-responsive">
                <table class="table table-balanced">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Status klaim</th>
                            <th>Kontak</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                        <tr>
                            <td>{{ $claim->item->name }}</td>
                            <td>
                                <span class="status-badge {{ $claim->status }}">{{ $claim->status_label }}</span>
                            </td>
                            <td>{{ $claim->contact }}</td>
                            <td>{{ optional($claim->claimed_at)->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="cell-actions">
                                <div class="table-actions-inline">
                                    <form action="{{ route('claims.destroy', $claim) }}" method="POST" onsubmit="return confirm('Hapus klaim ini?')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Hapus klaim">
                                            <span class="icon-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-note">
                                Belum ada klaim yang kamu ajukan.
                                <a href="{{ route('catalog') }}">Cari barang temuan</a>
                                dan ajukan klaim.
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
