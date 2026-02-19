@section('hide_chrome', true)
@extends('layout.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@section('user-content')
<div class="user-hero">
    <p class="eyebrow">Ringkasan</p>
    <h1 class="section-title">Dashboard Pengguna</h1>
    <p>Kelola laporan hilang yang kamu buat serta status klaim barang temuan.</p>
</div>

<div class="user-card">
    <h2 class="section-title" style="margin:0;font-size:1.2rem;">Status singkat</h2>
    <p class="card-desc" style="margin:0.25rem 0 0;">Kamu sudah masuk, lanjutkan membuat laporan atau cek status klaim.</p>
</div>
@endsection
