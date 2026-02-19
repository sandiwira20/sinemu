@extends('layout.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('admin-content')
<h1 class="section-title">Edit laporan barang hilang</h1>

<div class="report-card">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali perubahan laporan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="report-grid">
            <div class="form-group">
                <label>Jenis laporan</label>
                <select name="report_type" class="form-control" required>
                    <option value="lost" {{ old('report_type', $report->report_type) === 'lost' ? 'selected' : '' }}>Kehilangan</option>
                    <option value="found" {{ old('report_type', $report->report_type) === 'found' ? 'selected' : '' }}>Menemukan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $report->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nama barang</label>
                <input type="text" name="item_name" class="form-control" required value="{{ old('item_name', $report->item_name) }}">
            </div>

            <div class="form-group">
                <label>Lokasi hilang</label>
                <input type="text" name="lost_location" class="form-control" required value="{{ old('lost_location', $report->lost_location) }}">
            </div>
        </div>

        <div class="report-grid">
            <div class="form-group">
                <label>Waktu hilang</label>
                <input type="text" name="lost_at" class="form-control datetime-picker" required value="{{ old('lost_at', $report->lost_at->format('Y-m-d H:i')) }}">
            </div>
            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="contact" class="form-control" required value="{{ old('contact', $report->contact) }}">
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi barang / ciri khusus</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $report->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Bukti / foto (opsional)</label>
            @if($report->evidence_file)
            <div style="margin-bottom:0.4rem;">
                @php
                    $evidenceExt = strtolower(pathinfo($report->evidence_file, PATHINFO_EXTENSION));
                    $evidenceIsImage = in_array($evidenceExt, ['jpg', 'jpeg', 'png'], true);
                @endphp
                @if($evidenceIsImage)
                    <img src="{{ route('admin.reports.bukti', $report->id) }}" alt="Bukti laporan"
                        style="max-width:220px; display:block; margin-bottom:0.4rem;">
                @endif
                <a href="{{ route('admin.reports.bukti', $report->id) }}" target="_blank" rel="noopener">Lihat bukti saat ini</a>
            </div>
            @endif
            <input type="file" name="evidence" class="form-control js-file-input" data-max-mb="4"
                accept=".jpg,.jpeg,.png,.pdf">
            <div class="file-feedback" data-file-feedback></div>
            <small class="text-muted">Kosongkan jika tidak ingin mengganti. Max 4MB.</small>
        </div>
        @if($report->evidence_file)
        <div class="form-group create-item-option" style="margin-top:0;">
            <label for="remove_evidence">
                <input type="checkbox" id="remove_evidence" name="remove_evidence" value="1">
                <span>Hapus bukti saat ini</span>
            </label>
        </div>
        @endif

        <div class="form-group create-item-option">
            <label for="create_item">
                <input type="checkbox" id="create_item" name="create_item" value="1">
                <span>Salin laporan ini menjadi barang temuan</span>
            </label>
            <small class="text-muted helper">Pastikan kategori terisi agar barang temuan dapat dibuat.</small>
        </div>
        <div class="form-group">
            <label>Foto barang temuan (opsional)</label>
            <input type="file" name="item_image" class="form-control" accept=".jpg,.jpeg,.png">
            <small class="text-muted">Jika diisi, foto ini akan dipakai di homepage & katalog.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan perubahan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Kembali</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('.datetime-picker', {
        enableTime: true,
        dateFormat: 'Y-m-d\\TH:i',
        altInput: true,
        altFormat: 'd M Y, H:i',
        time_24hr: true,
        allowInput: true
    });
</script>
<script src="{{ asset('js/file-upload.js') }}"></script>
@endpush
