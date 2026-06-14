@extends('layouts.dashboard')
@section('title', 'Profile Mahasiswa')

@section('content')
<link rel="stylesheet" href="{{ asset('css/photo-cropper.css') }}">

<div class="page-header">
    <h1>Profile</h1>
    <p>Kelola informasi akun Anda</p>
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
            @if($user->mahasiswaDetail)
                <p class="body-sm text-muted mt-xs">{{ $user->mahasiswaDetail->universitas }} · NIM: {{ $user->mahasiswaDetail->nim }}</p>
            @endif
        </div>

        <form method="POST" action="{{ url('/mahasiswa/profile') }}" enctype="multipart/form-data" id="form-profile-mahasiswa">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label" for="nama">Nama Lengkap <span class="required">✱</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                @error('nama') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="foto_profile">Foto Profile</label>
                <input type="file" class="form-file" id="foto_profile" name="foto_profile" accept="image/*">
                <p class="body-sm text-muted mt-xs">Klik foto di atas atau pilih file untuk mengatur posisi crop</p>
            </div>

            <hr style="border:none;border-top:1px solid var(--outline-variant);margin:var(--space-lg) 0;">
            <h3 class="headline-sm mb-md">Ganti Password</h3>
            <p class="body-sm text-muted mb-md">Kosongkan jika tidak ingin mengganti password.</p>

            <div class="form-group">
                <label class="form-label" for="current_password">Password Lama</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Password saat ini">
                @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">Password Baru</label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="Min. 8 karakter">
                @error('new_password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/photo-cropper.js') }}"></script>
@endsection
