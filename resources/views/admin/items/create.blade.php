@extends('layout.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('.datetime-picker', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        time_24hr: true,
        allowInput: true
    });
</script>
@endpush

@section('admin-content')
<h1 class="section-title">Input barang temuan admin</h1>

<div class="report-card">
    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="report-grid">
            <div class="form-group">
                <label>Nama barang</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal & waktu ditemukan</label>
                <input type="text" name="found_at" class="form-control datetime-picker" required>
            </div>

            <div class="form-group">
                <label>Lokasi ditemukan</label>
                <input type="text" name="found_location" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi barang</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Lokasi penyimpanan</label>
            <input type="text" name="stored_location" class="form-control">
        </div>

        <div class="form-group">
            <label>Kontak</label>
            <input type="text" name="contact" class="form-control">
        </div>

        <div class="report-upload">
            <p>Pilih file atau seret dan jatuhkan di sini. Mendukung JPEG, PNG hingga 2MB.</p>
            <input type="file" name="main_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn" style="margin-top: 1.5rem;">
            Input sekarang
        </button>
    </form>
</div>
@endsection
