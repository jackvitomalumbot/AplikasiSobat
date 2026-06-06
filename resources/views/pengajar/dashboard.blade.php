@extends('layouts.dashboard')
@section('title', 'Dashboard Pengajar')

@section('content')
<div class="page-header">
    <h1>Selamat Datang, {{ auth()->user()->nama }}!</h1>
    <p>Ringkasan aktivitas mengajar Anda</p>
</div>

<div class="grid grid-4">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="stat-value">{{ $totalMahasiswa }}</div>
        <div class="stat-label">Total Mahasiswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon secondary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        </div>
        <div class="stat-value">{{ $totalKelas }}</div>
        <div class="stat-label">Kelas Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tertiary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-value">{{ $tugasBelumDinilai }}</div>
        <div class="stat-label">Tugas Belum Dinilai</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(137,245,231,.3);color:var(--on-secondary-fixed-variant);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
</div>

@if($kelasTerbaru->count())
<div class="mt-xl">
    <div class="d-flex justify-between align-center mb-lg">
        <h2 class="headline-md">Kelas Terbaru</h2>
        <a href="{{ url('/pengajar/kelas') }}" class="btn btn-ghost">Lihat Semua →</a>
    </div>
    <div class="grid grid-3">
        @foreach($kelasTerbaru as $kelas)
        <a href="{{ url('/pengajar/kelas/' . $kelas->id) }}" class="card card-kelas" style="text-decoration:none;color:inherit;">
            <div class="card-body">
                <span class="card-tag">{{ $kelas->active_enrollments_count ?? 0 }} Mahasiswa</span>
                <h3 class="card-title">{{ $kelas->nama_kelas }}</h3>
                <p class="card-desc">{{ $kelas->deskripsi }}</p>
            </div>
            <div class="card-footer">
                <span class="card-price">{{ $kelas->formatted_harga }}</span>
                <span class="badge badge-success">Aktif</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection
