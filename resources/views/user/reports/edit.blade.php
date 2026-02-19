@extends('layout.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('user-content')
<div class="user-hero">
    <p class="eyebrow">Edit Laporan</p>
    <h1 class="section-title">Perbarui barang hilang</h1>
    <p>Perbarui data laporanmu jika ada perubahan.</p>
</div>

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
    <form action="{{ route('reports.update', $report) }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ asset('storage/'.$report->evidence_file) }}" target="_blank">Lihat bukti saat ini</a>
            </div>
            @endif
            <input type="file" name="evidence" class="form-control js-file-input" data-max-mb="4"
                accept=".jpg,.jpeg,.png,.pdf">
            <div class="file-feedback" data-file-feedback></div>
            <small class="text-muted">Kosongkan jika tidak ingin mengganti. Max 4MB.</small>
        </div>

        @if(auth()->user()?->role?->name === 'admin')
        <div class="form-group create-item-option">
            <label for="create_item">
                <input type="checkbox" id="create_item" name="create_item" value="1">
                <span>Salin laporan ini menjadi barang temuan</span>
            </label>
            <small class="text-muted helper">Pastikan kategori terisi agar barang temuan dapat dibuat.</small>
        </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn">Simpan perubahan</button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline">Kembali</a>
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
