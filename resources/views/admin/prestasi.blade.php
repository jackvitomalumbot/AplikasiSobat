@extends('layouts.admin')
@section('title', 'Prestasi')

@section('content')
<div class="d-flex justify-between align-center flex-wrap gap-md">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Prestasi</h1>
        <p>Kelola prestasi yang ditampilkan di halaman Info publik.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-prestasi">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Prestasi
    </button>
</div>

<div class="table-wrapper mt-lg">
    <table class="table">
        <thead>
            <tr>
                <th style="width:56px;">Urutan</th>
                <th style="width:64px;">Foto</th>
                <th>Judul</th>
                <th style="width:140px;">Tipe</th>
                <th style="width:80px;">Status</th>
                <th style="width:100px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $p)
            <tr>
                <td style="text-align:center;font-weight:600;">{{ $p->urutan }}</td>
                <td>
                    <img src="{{ $p->foto_url }}" alt="{{ $p->judul }}"
                        style="width:48px;height:48px;border-radius:8px;object-fit:cover;border:1px solid rgba(0,0,0,0.1);">
                </td>
                <td>
                    <strong>{{ $p->judul }}</strong>
                    @if($p->deskripsi)
                    <p style="font-size:12px;color:var(--on-surface-variant);margin:2px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:300px;">{{ $p->deskripsi }}</p>
                    @endif
                </td>
                <td>
                    @php
                        $tipeColor = match($p->tipe) {
                            'featured'  => 'background:#fff3cd;color:#856404;',
                            'mahasiswa' => 'background:#d1ecf1;color:#0c5460;',
                            'pengajar'  => 'background:#d4edda;color:#155724;',
                            default     => 'background:var(--surface-container);color:var(--on-surface);',
                        };
                    @endphp
                    <span style="display:inline-block;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:600;{{ $tipeColor }}">
                        {{ $p->tipe_label }}
                    </span>
                </td>
                <td>
                    @if($p->aktif)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#2e7d32;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#2e7d32;"></span>Aktif
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:var(--on-surface-variant);">
                            <span style="width:7px;height:7px;border-radius:50%;background:var(--outline);"></span>Nonaktif
                        </span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-xs">
                        <button class="btn btn-secondary btn-sm"
                            onclick="openEdit({{ $p->id }}, {{ json_encode($p->judul) }}, {{ json_encode($p->deskripsi) }}, {{ json_encode($p->foto) }}, '{{ $p->tipe }}', {{ $p->urutan }}, {{ $p->aktif ? 'true' : 'false' }})"
                            title="Edit">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.prestasi.destroy', $p->id) }}"
                            onsubmit="return confirm('Hapus prestasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:var(--space-2xl);color:var(--on-surface-variant);">Belum ada prestasi. Tambahkan prestasi pertama!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── Modal Tambah ── --}}
<div class="modal" id="modal-add" hidden>
    <div class="modal-overlay" onclick="this.closest('.modal').hidden=true"></div>
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-header">
            <h3>Tambah Prestasi</h3>
            <button class="modal-close" onclick="document.getElementById('modal-add').hidden=true" aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.prestasi.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.prestasi-form')
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-add').hidden=true">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal Edit ── --}}
<div class="modal" id="modal-edit" hidden>
    <div class="modal-overlay" onclick="this.closest('.modal').hidden=true"></div>
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-header">
            <h3>Edit Prestasi</h3>
            <button class="modal-close" onclick="document.getElementById('modal-edit').hidden=true" aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="form-edit" action="" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.prestasi-form', ['editMode' => true])
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-edit').hidden=true">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btn-add-prestasi').addEventListener('click', () => {
    document.getElementById('modal-add').hidden = false;
});

function openEdit(id, judul, deskripsi, foto, tipe, urutan, aktif) {
    const form = document.getElementById('form-edit');
    form.action = '/admin/prestasi/' + id;
    form.querySelector('[name="judul"]').value     = judul || '';
    form.querySelector('[name="deskripsi"]').value = deskripsi || '';
    form.querySelector('[name="urutan"]').value    = urutan ?? 0;
    form.querySelector('[name="aktif"]').checked   = aktif;
    // Set tipe select
    const tipeSelect = form.querySelector('[name="tipe"]');
    if (tipeSelect) tipeSelect.value = tipe || 'featured';
    // Preview foto
    const preview = document.getElementById('edit_foto_preview');
    if (preview) {
        if (foto) {
            const isUrl = foto.startsWith('http://') || foto.startsWith('https://');
            preview.src = isUrl ? foto : '/' + foto;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
    document.getElementById('modal-edit').hidden = false;
}
</script>
@endpush
