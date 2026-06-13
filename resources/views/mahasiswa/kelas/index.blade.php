@extends('layouts.dashboard')
@section('title', 'Kelas Saya')

@section('content')
<div class="page-header">
    <h1>Kelas Saya</h1>
    <p>Kelas yang sudah Anda ikuti</p>
</div>

@if($enrolledKelas->count())
<div class="grid grid-3">
    @foreach($enrolledKelas as $kelas)
    <a href="{{ url('/mahasiswa/kelas/' . $kelas->id) }}" class="card card-kelas" style="text-decoration:none;color:inherit;">
        @if($kelas->thumbnail)
            <img src="{{ asset($kelas->thumbnail) }}" alt="{{ $kelas->nama_kelas }}" class="card-img-top">
        @else
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                <svg width="48" height="48" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
        @endif
        <div class="card-body">
            <div class="card-instructor mb-sm">
                <img src="{{ $kelas->pengajar->foto_profile ? asset($kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=32&background=cce5ff&color=004b73' }}" alt="">
                {{ $kelas->pengajar->nama }}
            </div>
            <h3 class="card-title">{{ $kelas->nama_kelas }}</h3>
            <p class="card-desc">{{ $kelas->deskripsi }}</p>
            <div class="mt-md">
                <div class="progress-label">
                    <span>Progress</span>
                    <span>{{ $kelas->submitted }}/{{ $kelas->total_tugas }} tugas</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $kelas->progress }}%"></div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="empty-state">
    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    <h3>Belum Ada Kelas</h3>
    <p>Mulai belajar dengan membeli kelas pertamamu.</p>
    <a href="{{ url('/mahasiswa/beli-kelas') }}" class="btn btn-primary">Cari Kelas</a>
</div>
@endif
@endsection
