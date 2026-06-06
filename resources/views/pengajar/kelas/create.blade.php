@extends('layouts.dashboard')
@section('title', 'Buat Kelas Baru')

@section('content')
<div class="page-header">
    <h1>Buat Kelas Baru</h1>
    <p>Isi detail kelas yang ingin Anda buat</p>
</div>

<div class="card" style="max-width:600px;cursor:default;">
    <div class="card-body">
        <form method="POST" action="{{ url('/pengajar/kelas') }}" enctype="multipart/form-data" id="form-create-kelas">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nama_kelas">Nama Kelas <span class="required">✱</span></label>
                <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="Contoh: Anatomi Dasar Semester 1" required maxlength="100">
                @error('nama_kelas') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="harga">Harga (Rp) <span class="required">✱</span></label>
                <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', 0) }}" min="0" step="1000" required>
                @error('harga') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi <span class="required">✱</span></label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" placeholder="Deskripsi singkat tentang kelas ini..." required maxlength="500">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="thumbnail">Thumbnail (Opsional)</label>
                <input type="file" class="form-file" id="thumbnail" name="thumbnail" accept="image/*">
                <span class="form-text">Format: JPG, PNG. Maksimal 2MB.</span>
                @error('thumbnail') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex gap-sm justify-end mt-lg">
                <a href="{{ url('/pengajar/kelas') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection
