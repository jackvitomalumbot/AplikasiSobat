@extends('layouts.dashboard')
@section('title', 'Beli Kelas')

@section('content')
<div class="page-header">
    <h1>Beli Kelas</h1>
    <p>Temukan kelas yang sesuai dengan kebutuhanmu</p>
</div>

<div class="search-bar">
    <form method="GET" action="{{ url('/mahasiswa/beli-kelas') }}" style="display:flex;gap:var(--space-sm);flex:1;">
        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari kelas, pengajar, atau topik...">
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request('search'))
            <a href="{{ url('/mahasiswa/beli-kelas') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

@if($availableKelas->count())
<div class="grid grid-3">
    @foreach($availableKelas as $kelas)
    <div class="card card-kelas">
        @if($kelas->thumbnail)
            <img src="{{ asset('storage/' . $kelas->thumbnail) }}" alt="{{ $kelas->nama_kelas }}" class="card-img-top">
        @else
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                <svg width="48" height="48" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
        @endif
        <div class="card-body">
            <div class="card-instructor">
                <img src="{{ $kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=32&background=cce5ff&color=004b73' }}" alt="">
                {{ $kelas->pengajar->nama }}
            </div>
            <h3 class="card-title">{{ $kelas->nama_kelas }}</h3>
            <p class="card-desc">{{ $kelas->deskripsi }}</p>
        </div>
        <div class="card-footer">
            <span class="card-price">{{ $kelas->formatted_harga }}</span>
            <a href="{{ url('/mahasiswa/beli-kelas/' . $kelas->id . '/checkout') }}" class="btn btn-primary btn-sm">Beli Sekarang</a>
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top: var(--space-lg);">
    {{ $availableKelas->withQueryString()->links() }}
</div>
@else
<div class="empty-state">
    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <h3>Tidak Ada Kelas Tersedia</h3>
    <p>{{ request('search') ? 'Coba gunakan kata kunci lain.' : 'Belum ada kelas baru untuk saat ini.' }}</p>
</div>
@endif
@endsection
