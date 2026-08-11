{{-- Shared form partial: dipakai oleh modal Tambah dan modal Edit --}}
<div class="modal-body">
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
        <label class="form-label">Foto — Upload File</label>
        <input type="file" name="foto_file" class="form-control" accept="image/*">
        <small style="color:var(--on-surface-variant);font-size:11px;">Maks. 2MB. Jika diisi, akan menggantikan URL foto.</small>
    </div>

    <div class="form-group">
        <label class="form-label">Foto — URL Gambar</label>
        <input type="url" name="foto_url" class="form-control"
            value="{{ old('foto_url', (isset($pu) && $pu && filter_var($pu->foto ?? '', FILTER_VALIDATE_URL)) ? $pu->foto : '') }}"
            placeholder="https://contoh.com/foto.jpg">
        <small style="color:var(--on-surface-variant);font-size:11px;">Isi salah satu: upload file atau URL. Upload file lebih diprioritaskan.</small>
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
