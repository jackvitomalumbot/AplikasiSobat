@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('auth_subtitle', 'Reset password akun Anda')
@section('content')
<p class="body-sm text-muted mb-lg">Masukkan email terdaftar Anda. Kami akan mengirimkan link untuk reset password.</p>
<form method="POST" action="{{ url('/forgot-password') }}" id="forgot-password-form">
    @csrf
    <div class="form-group">
        <label class="form-label" for="fp-email">Email <span class="required">✱</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="fp-email" name="email" value="{{ old('email') }}" placeholder="email@universitas.ac.id" required autofocus>
        @error('email') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg">Kirim Link Reset</button>
</form>
<div class="auth-footer"><a href="{{ url('/login') }}">← Kembali ke Login</a></div>
@endsection
