@extends('layouts.auth')
@section('title', 'Reset Password')
@section('auth_subtitle', 'Buat password baru')
@section('content')
<form method="POST" action="{{ url('/reset-password') }}" id="reset-password-form">
    @csrf
    <input type="hidden" name="token" value="{{ $token ?? '' }}">
    <input type="hidden" name="email" value="{{ $email ?? request()->email }}">
    <div class="form-group">
        <label class="form-label" for="rp-password">Password Baru <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="rp-password" name="password" placeholder="Min. 8 karakter" required>
            <button type="button" class="input-toggle" aria-label="Toggle"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
        @error('password') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="rp-password-confirm">Konfirmasi Password Baru <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control" id="rp-password-confirm" name="password_confirmation" placeholder="Ulangi password baru" required>
            <button type="button" class="input-toggle" aria-label="Toggle"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg">Reset Password</button>
</form>
@endsection
