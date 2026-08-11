@extends('layouts.admin')
@section('title', 'Pengajar Unggulan')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/photo-cropper.css') }}">
@endpush

@section('content')
<div class="d-flex justify-between align-center flex-wrap gap-md">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Pengajar Unggulan</h1>
        <p>Kelola tampilan pengajar unggulan di landing page. Maksimal 3 pengajar aktif yang tampil.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-pu" data-modal-target="#modal-add-pu">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pengajar Unggulan
    </button>
</div>

{{-- Info --}}
<div class="alert" style="background:var(--secondary-container);border:1px solid rgba(0,0,0,0.07);border-radius:var(--radius-sm);padding:var(--space-md) var(--space-lg);margin-top:var(--space-lg);font-size:var(--font-size-sm);color:var(--on-surface-variant);">
    <strong>ℹ️ Informasi:</strong>
    Landing page menampilkan maksimal <strong>3 pengajar unggulan</strong> yang berstatus <strong>Aktif</strong>, diurutkan berdasarkan nomor urutan.
    Data ini <strong>tidak terhubung</strong> ke akun pengajar manapun — dapat diisi bebas oleh admin.
</div>

{{-- Table --}}
<div class="table-wrapper" style="margin-top:var(--space-lg);">
    <table class="table">
        <thead>
            <tr>
                <th style="width:48px;">Urutan</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Spesialisasi</th>
                <th>Keahlian</th>
                <th style="width:80px;">Status</th>
                <th style="width:130px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $pu)
            <tr>
                <td style="text-align:center;font-weight:600;">{{ $pu->urutan }}</td>
                <td>
                    <img src="{{ $pu->foto_url }}"
                         alt="{{ $pu->nama }}"
                         style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:1px solid rgba(0,0,0,0.1);">
                </td>
                <td style="font-weight:600;">{{ $pu->nama }}</td>
                <td style="font-size:var(--font-size-sm);color:var(--on-surface-variant);">{{ $pu->spesialisasi ?? '—' }}</td>
                <td style="font-size:var(--font-size-xs);">
                    @foreach($pu->keahlian_array as $k)
                        <span style="display:inline-block;padding:2px 8px;background:var(--surface-container);border-radius:100px;margin:2px;font-family:var(--font-mono);font-size:10px;">{{ $k }}</span>
                    @endforeach
                </td>
                <td>
                    @if($pu->aktif)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#2e7d32;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#2e7d32;"></span> Aktif
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:var(--on-surface-variant);">
                            <span style="width:7px;height:7px;border-radius:50%;background:var(--outline);"></span> Nonaktif
                        </span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-xs">
                        <button class="btn btn-secondary btn-sm"
                            onclick="openEditPu({{ $pu->id }}, {{ json_encode($pu->nama) }}, {{ json_encode($pu->spesialisasi) }}, {{ json_encode($pu->foto) }}, {{ json_encode($pu->deskripsi) }}, {{ json_encode($pu->keahlian) }}, {{ json_encode($pu->motivasi) }}, {{ $pu->urutan }}, {{ $pu->aktif ? 'true' : 'false' }})"
                            title="Edit">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.pengajar-unggulan.destroy', $pu->id) }}"
                            onsubmit="return confirm('Hapus {{ $pu->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:var(--space-2xl);color:var(--on-surface-variant);">
                    Belum ada pengajar unggulan. Klik "Tambah Pengajar Unggulan" untuk mulai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div class="modal" id="modal-add-pu" hidden>
    <div class="modal-overlay" onclick="document.getElementById('modal-add-pu').hidden=true"></div>
    <div class="modal-box" style="max-width:600px;">
        <div class="modal-header">
            <h3>Tambah Pengajar Unggulan</h3>
            <button class="modal-close" onclick="document.getElementById('modal-add-pu').hidden=true" aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengajar-unggulan.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.pengajar-unggulan-form', ['pu' => null])
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-add-pu').hidden=true">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal" id="modal-edit-pu" hidden>
    <div class="modal-overlay" onclick="document.getElementById('modal-edit-pu').hidden=true"></div>
    <div class="modal-box" style="max-width:600px;">
        <div class="modal-header">
            <h3>Edit Pengajar Unggulan</h3>
            <button class="modal-close" onclick="document.getElementById('modal-edit-pu').hidden=true" aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="form-edit-pu" action="" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.pengajar-unggulan-form', ['pu' => null, 'editMode' => true])
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-edit-pu').hidden=true">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/photo-cropper.js') }}"></script>
<script>
function openEditPu(id, nama, spesialisasi, foto, deskripsi, keahlian, motivasi, urutan, aktif) {
    const form = document.getElementById('form-edit-pu');
    form.action = '/admin/pengajar-unggulan/' + id;

    form.querySelector('[name="nama"]').value = nama || '';
    form.querySelector('[name="spesialisasi"]').value = spesialisasi || '';
    form.querySelector('[name="deskripsi"]').value = deskripsi || '';
    form.querySelector('[name="keahlian"]').value = keahlian || '';
    form.querySelector('[name="motivasi"]').value = motivasi || '';
    form.querySelector('[name="urutan"]').value = urutan ?? 0;
    form.querySelector('[name="aktif"]').checked = aktif;

    // Update foto preview
    const preview = document.getElementById('edit_foto_preview');
    if (preview) {
        if (foto) {
            // Jika URL langsung atau path public
            const isUrl = foto.startsWith('http://') || foto.startsWith('https://');
            preview.src = isUrl ? foto : '/' + foto;
        } else {
            preview.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nama || 'Foto') + '&size=128&background=e9e2cc&color=635e4d';
        }
    }

    document.getElementById('modal-edit-pu').hidden = false;
}

// Tombol Tambah
document.getElementById('btn-add-pu').addEventListener('click', function() {
    document.getElementById('modal-add-pu').hidden = false;
});
</script>
@endpush
