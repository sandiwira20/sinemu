@extends('layout.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('user-content')
<h1 class="section-title">Laporkan barang hilang</h1>

<div class="report-card">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data laporan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="report-grid">
            <div class="form-group">
                <label>Jenis laporan</label>
                <select name="report_type" class="form-control" required>
                    <option value="lost" {{ old('report_type') === 'lost' ? 'selected' : '' }}>Kehilangan</option>
                    <option value="found" {{ old('report_type') === 'found' ? 'selected' : '' }}>Menemukan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nama barang</label>
                <input type="text" name="item_name" class="form-control" required value="{{ old('item_name') }}">
            </div>

            <div class="form-group">
                <label>Lokasi hilang</label>
                <input type="text" name="lost_location" class="form-control" required value="{{ old('lost_location') }}">
            </div>
        </div>

        <div class="report-grid">
            <div class="form-group">
                <label>Waktu hilang</label>
                <input type="text" name="lost_at" class="form-control datetime-picker" required value="{{ old('lost_at') }}">
            </div>
            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="contact" class="form-control" placeholder="Nomor HP / Email" required value="{{ old('contact') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi barang / ciri khusus</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Bukti / foto (opsional)</label>
            <input type="file" name="evidence" class="form-control js-file-input" data-max-mb="4"
                accept=".jpg,.jpeg,.png,.pdf">
            <div class="file-feedback" data-file-feedback></div>
            <small class="text-muted">Mendukung JPEG, PNG, atau PDF hingga 4MB.</small>
        </div>

        <button type="submit" class="btn" style="margin-top: 1.5rem;">
            Kirim laporan
        </button>
    </form>
</div>
@endsection

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
