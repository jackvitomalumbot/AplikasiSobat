{{-- Form fields untuk Tambah/Edit Prestasi --}}
<div style="padding:var(--space-lg);display:flex;flex-direction:column;gap:var(--space-md);">

    {{-- Judul --}}
    <div class="form-group">
        <label class="form-label">Judul Prestasi <span style="color:red;">*</span></label>
        <input type="text" name="judul" class="form-input" placeholder="Contoh: Juara 1 Olimpiade Kedokteran Nasional" required>
    </div>

    {{-- Tipe --}}
    <div class="form-group">
        <label class="form-label">Tipe <span style="color:red;">*</span></label>
        <select name="tipe" class="form-input" required>
            <option value="featured">⭐ Prestasi Utama (Featured)</option>
            <option value="mahasiswa">🎓 Prestasi Mahasiswa</option>
            <option value="pengajar">👨‍⚕️ Prestasi Pengajar</option>
        </select>
    </div>

    {{-- Deskripsi --}}
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-input" rows="3"
            placeholder="Deskripsi singkat tentang prestasi ini..." style="resize:vertical;"></textarea>
    </div>

    {{-- Foto --}}
    <div class="form-group">
        <label class="form-label">Foto</label>
        @isset($editMode)
        <img id="edit_foto_preview" src="" alt="Preview"
            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,0.1);margin-bottom:8px;display:none;">
        @endisset
        <input type="file" name="foto_file" class="form-input" accept="image/*"
            style="padding:6px;" id="{{ isset($editMode) ? 'edit_foto_file' : 'add_foto_file' }}">
        <p style="font-size:11px;color:var(--on-surface-variant);margin-top:4px;">Format: JPG, PNG, WEBP. Maks 4MB.</p>
    </div>

    {{-- Urutan & Aktif --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
        <div class="form-group">
            <label class="form-label">Nomor Urutan</label>
            <input type="number" name="urutan" class="form-input" value="0" min="0">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:var(--font-size-sm);">
                <input type="checkbox" name="aktif" value="1" checked style="width:16px;height:16px;">
                Tampilkan di halaman Info
            </label>
        </div>
    </div>
</div>
