@extends('layouts.dashboard')
@section('title', 'Profile Pengajar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/photo-cropper.css') }}">

<div class="page-header">
    <h1>Profile</h1>
    <p>Kelola informasi profile Anda</p>
</div>

<div class="card" style="max-width:600px;cursor:default;">
    <div class="card-body">
        <div class="text-center mb-xl">
            <div class="photo-cropper-trigger" data-photo-cropper data-input-id="foto_profile">
                <img src="{{ $user->foto_profile ? asset($user->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&size=128&background=cce5ff&color=004b73' }}" alt="{{ $user->nama }}" class="avatar avatar-xl" style="margin:0 auto;">
                <div class="photo-cropper-overlay">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <span>Ubah Foto</span>
                </div>
            </div>
            <h2 class="headline-md mt-md">{{ $user->nama }}</h2>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ url('/pengajar/profile') }}" enctype="multipart/form-data" id="form-profile-pengajar">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label" for="foto_profile">Foto Profile</label>
                <input type="file" class="form-file" id="foto_profile" name="foto_profile" accept="image/*">
                <p class="body-sm text-muted mt-xs">Klik foto di atas atau pilih file untuk mengatur posisi crop</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="spesialisasi">Spesialisasi</label>
                <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="{{ $user->pengajarDetail->spesialisasi ?? '' }}" placeholder="Contoh: Anatomi & Fisiologi">
            </div>

            <div class="form-group">
                <label class="form-label" for="kontak">Nomor Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" value="{{ $user->pengajarDetail->kontak ?? '' }}" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" placeholder="Ceritakan tentang diri Anda...">{{ $user->pengajarDetail->bio ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/photo-cropper.js') }}"></script>
@endsection
