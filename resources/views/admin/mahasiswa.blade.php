@extends('layouts.admin')
@section('title', 'Data Mahasiswa')

@section('content')
<div class="d-flex justify-between align-center flex-wrap gap-md">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Data Mahasiswa</h1>
        <p>Kelola semua mahasiswa terdaftar</p>
    </div>
    <a href="{{ route('admin.mahasiswa.pdf') }}" class="btn btn-outline" target="_blank">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Download PDF
    </a>
</div>

<div class="search-bar mt-lg">
    <form method="GET" action="{{ url('/admin/mahasiswa') }}" style="display:flex;gap:var(--space-sm);flex:1;">
        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, NIM, atau universitas...">
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request('search'))
            <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

<div class="table-wrapper">
    <table class="table" id="table-mahasiswa">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Universitas</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswaList as $i => $mhs)
            <tr>
                <td>{{ $mahasiswaList->firstItem() + $i }}</td>
                <td>
                    <div class="d-flex align-center gap-sm">
                        <img src="{{ $mhs->foto_profile ? asset($mhs->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($mhs->nama) . '&size=40&background=cce5ff&color=004b73' }}" alt="" class="avatar" style="width:32px;height:32px;">
                        {{ $mhs->nama }}
                    </div>
                </td>
                <td>{{ $mhs->email }}</td>
                <td>{{ $mhs->mahasiswaDetail->nim ?? '-' }}</td>
                <td>{{ $mhs->mahasiswaDetail->universitas ?? '-' }}</td>
                <td>{{ $mhs->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex gap-xs">
                        <button class="btn btn-outline btn-sm" onclick="openPasswordModal({{ $mhs->id }}, '{{ addslashes($mhs->nama) }}')">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Password
                        </button>
                        <form method="POST" action="{{ url('/admin/users/' . $mhs->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus mahasiswa {{ $mhs->nama }}?">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted" style="padding:var(--space-xl);">Belum ada mahasiswa terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: var(--space-lg);">
    {{ $mahasiswaList->withQueryString()->links() }}
</div>

{{-- Modal: Ganti Password --}}
<div class="modal-overlay" id="modal-change-password">
    <div class="modal">
        <div class="modal-header">
            <h3>Ganti Password <span id="password-user-name"></span></h3>
            <button class="modal-close" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="form-change-password">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="alert alert-warning mb-md" style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--radius-sm);background:var(--tertiary-container);color:var(--on-tertiary-container);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Password akan langsung diganti. Pastikan sudah benar!
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="required">✱</span></label>
                    <input type="password" class="form-control" name="new_password" placeholder="Min. 8 karakter" required minlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="required">✱</span></label>
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Ulangi password baru" required minlength="8">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openPasswordModal(userId, userName) {
    document.getElementById('password-user-name').textContent = userName;
    document.getElementById('form-change-password').action = '/admin/users/' + userId + '/password';
    const modal = document.getElementById('modal-change-password');
    modal.classList.add('active');
    modal.querySelector('input[name="new_password"]').value = '';
    modal.querySelector('input[name="new_password_confirmation"]').value = '';
}
</script>
@endpush
@endsection
