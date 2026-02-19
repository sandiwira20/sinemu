@extends('layout.admin')

@section('admin-content')
<div class="card">
    <div class="card-head">
        <p class="eyebrow" style="margin:0;">Detail klaim</p>
        <h3 class="section-title" style="margin:0.15rem 0 0;">{{ $claim->item->name ?? 'Barang' }}</h3>
        <p class="table-subtext" style="margin:0;">Diajukan oleh {{ $claim->user->name }} pada {{ $claim->claimed_at?->format('d M Y, H:i') }}</p>
    </div>

    <div class="detail-grid">
        <div>
            <p class="detail-label">Pengaju</p>
            <p class="detail-value">{{ $claim->user->name }}</p>
            <p class="detail-sub">{{ $claim->user->email }}</p>
        </div>
        <div>
            <p class="detail-label">Kontak</p>
            <p class="detail-value">{{ $claim->contact ?? '-' }}</p>
        </div>
        <div>
            <p class="detail-label">Status</p>
            <span class="status-pill status-{{ $claim->status }}">{{ $claim->status_label }}</span>
            @if($claim->verified_at)
            <p class="detail-sub">Diverifikasi {{ $claim->verified_at->format('d M Y, H:i') }} oleh {{ $claim->verifier->name ?? 'Admin' }}</p>
            @endif
        </div>
        <div>
            <p class="detail-label">Barang</p>
            <p class="detail-value">{{ $claim->item->name }}</p>
            <p class="detail-sub">ID: {{ $claim->item->id }}</p>
        </div>
    </div>

    <div class="detail-section">
        <p class="detail-label">Deskripsi klaim</p>
        <p class="detail-body">{{ $claim->description ?: 'Tidak ada deskripsi yang diberikan.' }}</p>
    </div>

    <div class="detail-section">
        <p class="detail-label">Bukti pendukung</p>
        @if($claim->evidence_file)
        @php
            $evidenceExt = strtolower(pathinfo($claim->evidence_file, PATHINFO_EXTENSION));
            $evidenceIsImage = in_array($evidenceExt, ['jpg', 'jpeg', 'png'], true);
        @endphp
        @if($evidenceIsImage)
        <img src="{{ asset('storage/'.$claim->evidence_file) }}" alt="Bukti klaim"
            style="max-width:260px; display:block; margin-bottom:0.5rem;">
        @endif
        <a class="btn-chip ghost" href="{{ asset('storage/'.$claim->evidence_file) }}" target="_blank" rel="noopener">
            <span class="icon-18 icon-eye"></span>
            Lihat bukti
        </a>
        @else
        <p class="detail-body">Tidak ada file bukti.</p>
        @endif
    </div>

    <div class="detail-actions">
        <a href="{{ route('admin.claims.index') }}" class="btn-chip ghost sm">Kembali</a>
        @if($claim->status === 'pending')
        <form action="{{ route('admin.claims.verify', $claim) }}" method="POST" style="display:inline-flex; gap:0.4rem;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="btn-chip primary sm">Setujui</button>
        </form>
        <form action="{{ route('admin.claims.verify', $claim) }}" method="POST" style="display:inline-flex; gap:0.4rem;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="btn-chip danger sm">Tolak</button>
        </form>
        @endif
    </div>
</div>
@endsection
