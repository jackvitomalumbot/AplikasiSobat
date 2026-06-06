@extends('layouts.auth')

@section('title', 'Login')
@section('auth_subtitle', 'Masuk ke akun Anda')

@section('content')
<form method="POST" action="{{ url('/login') }}" id="login-form">
    @csrf

    <div class="form-group">
        <label class="form-label" for="login-email">Email <span class="required">✱</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" value="{{ old('email') }}" placeholder="email@universitas.ac.id" required autofocus>
        @error('email')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="login-password">Password <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" placeholder="Masukkan password" required>
            <button type="button" class="input-toggle" aria-label="Toggle password visibility">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
        </div>
        @error('password')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="d-flex justify-between align-center mb-lg">
        <label class="d-flex align-center gap-sm body-sm" style="cursor: pointer;">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
        </label>
        <a href="{{ url('/forgot-password') }}" class="body-sm">Lupa password?</a>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-submit-login">
        Masuk
    </button>
</form>

<div class="auth-footer">
    Belum punya akun? <a href="{{ url('/register') }}"><strong>Daftar sekarang</strong></a>
</div>
@endsection
