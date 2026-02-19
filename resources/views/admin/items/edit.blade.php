@extends('layout.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('admin-content')
<h1 class="section-title">Edit barang temuan</h1>

<div class="report-card">
    <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="report-grid">
            <div class="form-group">
                <label>Nama barang</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $item->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Lokasi ditemukan</label>
                <input type="text" name="found_location" class="form-control" value="{{ old('found_location', $item->found_location) }}" required>
            </div>

            <div class="form-group">
                <label>Tanggal ditemukan</label>
                <input type="date" name="found_at" class="form-control" value="{{ old('found_at', optional($item->found_at)->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label>Lokasi penyimpanan</label>
                <input type="text" name="stored_location" class="form-control" value="{{ old('stored_location', $item->stored_location) }}">
            </div>

            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $item->contact) }}">
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Gambar utama</label><br>
            @if($item->main_image)
            <img src="{{ asset('storage/'.$item->main_image) }}" alt="{{ $item->name }}" style="max-width:160px; display:block; margin-bottom:0.6rem;">
            @endif
            <input type="file" name="main_image" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan perubahan</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
