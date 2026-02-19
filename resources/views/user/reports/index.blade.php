@extends('layout.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@section('user-content')
<div class="user-hero reports-hero">
    <p class="eyebrow">Laporan Saya</p>
    <h1 class="section-title">Laporan barang hilang</h1>
    <p>Kelola laporanmu: lihat, edit, atau hapus jika diperlukan.</p>
    <div class="hero-actions" style="margin-top:0.8rem; display:flex; gap:0.6rem; flex-wrap:wrap;">
        <a href="{{ route('reports.create') }}" class="btn">Buat laporan baru</a>
    </div>
    @if(session('success'))
    <div class="user-card" style="margin-top:0.8rem;padding:0.75rem 1rem;">
        {{ session('success') }}
    </div>
    @endif
</div>

<div class="user-card reports-card">
    <table class="table">
        <thead>
            <tr>
                <th>Nama barang</th>
                <th>Jenis</th>
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
                <td>{{ $report->report_type === 'found' ? 'Menemukan' : 'Kehilangan' }}</td>
                <td>{{ $report->lost_location }}</td>
                <td>{{ $report->lost_at->format('d/m/Y H:i') }}</td>
                <td><span class="status-badge {{ $report->status }}">{{ $report->status_label }}</span></td>
                <td style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
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
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-note">
                        Belum ada laporan yang kamu kirim.
                        <a href="{{ route('reports.create') }}">Buat laporan pertama</a>
                        atau
                        <a href="{{ route('catalog') }}">cek barang temuan</a>.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $reports->links() }}
    </div>
</div>
@endsection
