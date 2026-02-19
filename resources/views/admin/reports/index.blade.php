@extends('layout.admin')

@section('admin-content')
    <div class="admin-hero hero-block">
        <div>
            <p class="eyebrow">Manajemen laporan</p>
            <h1 class="section-title" style="margin:0;">Laporan barang hilang</h1>
            <p style="margin:0.25rem 0 0;color:#4a5c7a;">Pantau status tindak lanjut laporan pengguna.</p>
        </div>
        <div class="hero-stats">
            <div class="stat-chip">
                <span>Terbuka</span>
                <strong>{{ $stats['open'] ?? 0 }}</strong>
            </div>
            <div class="stat-chip">
                <span>Cocok</span>
                <strong>{{ $stats['matched'] ?? 0 }}</strong>
            </div>
            <div class="stat-chip">
                <span>Ditutup</span>
                <strong>{{ $stats['closed'] ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <p class="eyebrow" style="margin:0;">Daftar laporan</p>
                <p class="table-subtext">Cek detail, ubah status, atau hapus laporan yang tidak valid.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-comfy table-reports">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Barang</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Lokasi hilang</th>
                        <th>Kontak</th>
                        <th>Waktu hilang</th>
                        <th>Status</th>
                        <th style="min-width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <div class="cell-title">{{ $report->user->name }}</div>
                                <div class="cell-muted">{{ $report->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="cell-title">{{ $report->item_name }}</div>
                                <div class="pill muted" style="margin-top:0.25rem; display:inline-block;">
                                    {{ $report->report_type === 'found' ? 'Menemukan' : 'Kehilangan' }}
                                </div>
                            </td>
                            <td>{{ $report->report_type === 'found' ? 'Menemukan' : 'Kehilangan' }}</td>
                            <td>{{ optional($report->category)->name ?? '-' }}</td>
                            <td>{{ $report->lost_location }}</td>
                            <td>{{ $report->contact ?? '-' }}</td>
                            <td>{{ $report->lost_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="status-pill status-{{ $report->status }}">
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <div class="action-row action-icons">
                                        @if($report->evidence_file)
                                            <a href="{{ route('admin.reports.bukti', $report->id) }}"
                                                class="icon-action ghost compact" target="_blank" title="Lihat bukti">
                                                <span class="icon-18 icon-eye"></span>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.reports.edit', $report) }}" class="icon-action ghost compact"
                                            title="Edit laporan">
                                            <span class="icon-18 icon-pencil"></span>
                                        </a>
                                        <button type="submit" form="update-status-{{ $report->id }}" class="icon-action compact"
                                            title="Update status">
                                            <span class="icon-18 icon-refresh"></span>
                                        </button>
                                        <form action="{{ route('admin.reports.destroy-admin', $report) }}" method="POST"
                                            class="action-row" onsubmit="return confirm('Hapus laporan ini?')"
                                            style="display:inline-flex;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-action danger compact" title="Hapus laporan">
                                                <span class="icon-18 icon-trash"></span>
                                            </button>
                                        </form>
                                    </div>
                                    <form id="update-status-{{ $report->id }}"
                                        action="{{ route('admin.reports.update-status', $report) }}" method="POST"
                                        class="action-row">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="status-select">
                                            <option value="open" {{ $report->status === 'open' ? 'selected' : '' }}>Terbuka</option>
                                            <option value="matched" {{ $report->status === 'matched' ? 'selected' : '' }}>Cocok</option>
                                            <option value="closed" {{ $report->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <p class="empty-title">Belum ada laporan</p>
                                <p class="empty-subtext">Menunggu laporan baru dari pengguna.</p>
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
            {{ $reports->links() }}
        </div>
    </div>
@endsection
