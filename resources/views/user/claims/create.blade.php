@extends('layout.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('user-content')
<h1 class="section-title">Klaim barang: {{ $item->name }}</h1>

<div class="report-card">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data klaim.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('claims.store', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Ciri-ciri khusus tambahan</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="report-grid">
            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="contact" class="form-control" required value="{{ old('contact') }}">
            </div>
            <div></div>
        </div>

        <div class="report-upload">
            <p>Pilih file atau seret dan jatuhkan di sini. Mendukung JPEG, PNG, PDF hingga 4MB.</p>
            <input id="evidence" type="file" name="evidence" class="form-control js-file-input" data-max-mb="4"
                accept=".jpg,.jpeg,.png,.pdf">
            <div class="file-feedback" data-file-feedback></div>
        </div>

        <button type="submit" class="btn" style="margin-top: 1.5rem;">
            Ajukan klaim sekarang
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/file-upload.js') }}"></script>
@endpush
