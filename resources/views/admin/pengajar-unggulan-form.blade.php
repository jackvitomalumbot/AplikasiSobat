{{-- Shared form partial: dipakai oleh modal Tambah dan modal Edit --}}

{{-- Foto dengan Photo Cropper --}}
<div class="modal-body">
    {{-- Foto Preview + Trigger --}}
    <div class="form-group">
        <label class="form-label">Foto Pengajar</label>
        <div style="display:flex;align-items:center;gap:var(--space-lg);flex-wrap:wrap;">
            {{-- Preview Avatar --}}
            <div class="photo-cropper-trigger"
                data-photo-cropper
                data-input-id="{{ isset($editMode) && $editMode ? 'edit_foto_file' : 'add_foto_file' }}"
                data-crop-size="220"
                data-output-size="400"
                style="flex-shrink:0;">
                <img id="{{ isset($editMode) && $editMode ? 'edit_foto_preview' : 'add_foto_preview' }}"
                    src="{{ ($pu && $pu->foto) ? $pu->foto_url : 'https://ui-avatars.com/api/?name=Foto&size=128&background=e9e2cc&color=635e4d' }}"
                    alt="Preview Foto"
                    class="avatar"
                    style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:2px solid var(--outline-variant);">
                <div class="photo-cropper-overlay">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <span style="font-size:11px;">Ubah Foto</span>
                </div>
            </div>

            <div style="flex:1;min-width:180px;">
                {{-- Hidden file input (dikendalikan oleh cropper) --}}
                <input type="file"
                    id="{{ isset($editMode) && $editMode ? 'edit_foto_file' : 'add_foto_file' }}"
                    name="foto_file"
                    accept="image/*"
                    style="display:none;">

                <p style="font-size:var(--font-size-sm);color:var(--on-surface-variant);margin:0 0 var(--space-sm);">
                    Klik foto untuk memilih dan mengatur posisi crop (lingkaran 1:1).
                </p>
                <p style="font-size:11px;color:var(--on-surface-variant);margin:0;">
                    Format: JPG, PNG, WebP · Maks. 4MB
                </p>

                {{-- Tombol manual jika trigger tidak berfungsi --}}
                <button type="button"
                    onclick="document.getElementById('{{ isset($editMode) && $editMode ? 'edit_foto_file' : 'add_foto_file' }}').click()"
                    class="btn btn-outline btn-sm"
                    style="margin-top:var(--space-sm);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Pilih Foto
                </button>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="nama" class="form-control"
            value="{{ old('nama', $pu->nama ?? '') }}"
            placeholder="Contoh: Dr. Nesya Cendranita" required>
    </div>

    <div class="form-group">
        <label class="form-label">Spesialisasi / Bidang</label>
        <input type="text" name="spesialisasi" class="form-control"
            value="{{ old('spesialisasi', $pu->spesialisasi ?? '') }}"
            placeholder="Contoh: Obstetri & Ginekologi">
    </div>

    <div class="form-group">
        <label class="form-label">Deskripsi Singkat</label>
        <textarea name="deskripsi" class="form-control" rows="3"
            placeholder="Deskripsi pengajar yang muncul di detail profil...">{{ old('deskripsi', $pu->deskripsi ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label class="form-label">Keahlian</label>
        <input type="text" name="keahlian" class="form-control"
            value="{{ old('keahlian', $pu->keahlian ?? '') }}"
            placeholder="Kehamilan|Persalinan|Kesehatan Reproduksi">
        <small style="color:var(--on-surface-variant);font-size:11px;">Pisahkan dengan tanda <strong>|</strong> (pipe). Contoh: <code>EKG|Kardiologi|Hipertensi</code></small>
    </div>

    <div class="form-group">
        <label class="form-label">Kutipan Motivasi</label>
        <input type="text" name="motivasi" class="form-control"
            value="{{ old('motivasi', $pu->motivasi ?? '') }}"
            placeholder='"Belajar tidak mengenal batas usia."'>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
        <div class="form-group">
            <label class="form-label">Nomor Urutan</label>
            <input type="number" name="urutan" class="form-control"
                value="{{ old('urutan', $pu->urutan ?? 0) }}"
                min="0" placeholder="0">
            <small style="color:var(--on-surface-variant);font-size:11px;">Urutan terkecil tampil pertama.</small>
        </div>
        <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;padding-bottom:var(--space-sm);">
            <label style="display:flex;align-items:center;gap:var(--space-sm);cursor:pointer;">
                <input type="checkbox" name="aktif" value="1"
                    {{ old('aktif', $pu->aktif ?? true) ? 'checked' : '' }}
                    style="width:16px;height:16px;cursor:pointer;">
                <span style="font-size:var(--font-size-sm);font-weight:500;">Tampilkan di Landing Page</span>
            </label>
        </div>
    </div>
</div>
