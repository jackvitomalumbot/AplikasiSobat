@extends('layouts.dashboard')
@section('title', 'Profile Pengajar')

@section('content')
<div class="page-header">
    <h1>Profile</h1>
    <p>Kelola informasi profile Anda</p>
</div>

<div class="card" style="max-width:600px;cursor:default;">
    <div class="card-body">
        <div class="text-center mb-xl">
            <img src="{{ $user->foto_profile ? asset('storage/'.$user->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&size=128&background=cce5ff&color=004b73' }}" alt="{{ $user->nama }}" class="avatar avatar-xl" style="margin:0 auto;">
            <h2 class="headline-md mt-md">{{ $user->nama }}</h2>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ url('/pengajar/profile') }}" enctype="multipart/form-data" id="form-profile-pengajar">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label" for="foto_profile">Foto Profile</label>
                <input type="file" class="form-file" id="foto_profile" name="foto_profile" accept="image/*">
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
@endsection
