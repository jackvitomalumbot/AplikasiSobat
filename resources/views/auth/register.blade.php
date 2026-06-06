@extends('layouts.auth')

@section('title', 'Daftar Akun')
@section('auth_subtitle', 'Buat akun mahasiswa baru')

@section('content')
<form method="POST" action="{{ url('/register') }}" id="register-form">
    @csrf

    <div class="form-group">
        <label class="form-label" for="reg-name">Nama Lengkap <span class="required">✱</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="reg-name" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap Anda" required>
        @error('nama')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="reg-universitas">Nama Universitas <span class="required">✱</span></label>
        <input type="text" class="form-control @error('universitas') is-invalid @enderror" id="reg-universitas" name="universitas" value="{{ old('universitas') }}" placeholder="Contoh: Universitas Indonesia" required>
        @error('universitas')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="reg-nim">NIM <span class="required">✱</span></label>
        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="reg-nim" name="nim" value="{{ old('nim') }}" placeholder="Nomor Induk Mahasiswa" required>
        @error('nim')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="reg-email">Email <span class="required">✱</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="reg-email" name="email" value="{{ old('email') }}" placeholder="email@universitas.ac.id" required>
        <span class="form-text">Email akan digunakan untuk verifikasi akun.</span>
        @error('email')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="reg-password">Password <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="reg-password" name="password" placeholder="Min. 8 karakter" required>
            <button type="button" class="input-toggle" aria-label="Toggle password visibility">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
        </div>
        @error('password')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="reg-password-confirm">Konfirmasi Password <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control" id="reg-password-confirm" name="password_confirmation" placeholder="Ulangi password" required>
            <button type="button" class="input-toggle" aria-label="Toggle password visibility">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-submit-register">
        Daftar Sekarang
    </button>
</form>

<div class="auth-footer">
    Sudah punya akun? <a href="{{ url('/login') }}"><strong>Masuk di sini</strong></a>
</div>
@endsection
