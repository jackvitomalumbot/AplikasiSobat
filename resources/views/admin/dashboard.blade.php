@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Ringkasan data platform SobatMedis</p>
</div>

<div class="grid grid-4">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalMahasiswa) }}</div>
        <div class="stat-label">Total Mahasiswa</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon secondary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalPengajar) }}</div>
        <div class="stat-label">Total Pengajar</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon tertiary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalKelas) }}</div>
        <div class="stat-label">Total Kelas</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon error">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalEnrollments) }}</div>
        <div class="stat-label">Total Enrollment</div>
    </div>
</div>
@endsection
