@extends('layouts.dashboard')
@section('title', $kelas->nama_kelas)

@section('content')
<div class="d-flex justify-between align-center flex-wrap gap-md mb-lg">
    <div>
        <a href="{{ url('/pengajar/kelas') }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Manajemen Kelas
        </a>
        <h1 class="headline-lg">{{ $kelas->nama_kelas }}</h1>
        <p class="text-muted">{{ $kelas->deskripsi }}</p>
    </div>
    <div class="d-flex gap-sm flex-wrap">
        <button class="btn btn-primary" data-modal-target="#modal-add-pertemuan" id="btn-add-pertemuan">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Pertemuan/Tugas
        </button>
        <a href="{{ route('pengajar.absensi.pdf', $kelas) }}" class="btn btn-outline" target="_blank">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Download Rekap Absensi (PDF)
        </a>
        <form method="POST" action="{{ url('/pengajar/kelas/' . $kelas->id) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" data-confirm="Hapus kelas {{ $kelas->nama_kelas }}? Semua data akan hilang.">Hapus Kelas</button>
        </form>
    </div>
</div>

{{-- Enrolled students --}}
<div class="card mb-lg" style="cursor:default;">
    <div class="card-header">
        <h3 class="headline-sm">Mahasiswa Terdaftar ({{ $kelas->activeEnrollments->count() }})</h3>
    </div>
    <div class="card-body" style="padding:0;">
        @if($kelas->activeEnrollments->count())
        <div style="max-height:200px;overflow-y:auto;">
            @foreach($kelas->activeEnrollments as $enrollment)
            <div class="deadline-item">
                <img src="{{ $enrollment->mahasiswa->foto_profile ? asset('storage/'.$enrollment->mahasiswa->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($enrollment->mahasiswa->nama).'&size=40&background=cce5ff&color=004b73' }}" alt="" class="avatar" style="width:36px;height:36px;">
                <div class="deadline-content">
                    <div class="deadline-title">{{ $enrollment->mahasiswa->nama }}</div>
                    <div class="deadline-meta">{{ $enrollment->mahasiswa->email }}</div>
                </div>
                <span class="badge badge-success">Aktif</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted" style="padding:var(--space-lg);">Belum ada mahasiswa terdaftar.</p>
        @endif
    </div>
</div>

{{-- Timeline Pertemuan & Tugas --}}
<h2 class="headline-md mb-lg">Jadwal Pertemuan & Tugas</h2>

@if($kelas->pertemuan->count())
<ul class="timeline">
    @foreach($kelas->pertemuan as $item)
    <li class="timeline-item {{ $item->isTugas() ? 'task' : '' }}">
        <div class="timeline-date">
            {{ $item->tanggal->format('d M Y') }}
            · <span class="badge {{ $item->isTugas() ? 'badge-warning' : 'badge-primary' }}">{{ $item->isTugas() ? 'Tugas' : 'Pertemuan' }}</span>
            @php
                $hadirCount = $item->absensi->where('status', 'hadir')->count();
                $totalEnrolled = $kelas->activeEnrollments->count();
            @endphp
            @if($hadirCount > 0)
                <span class="badge badge-success">{{ $hadirCount }}/{{ $totalEnrolled }} hadir</span>
            @endif
            @if($item->isTugas())
                @php $subCount = $item->tugasSubmissions->count(); @endphp
                <span class="badge badge-neutral">{{ $subCount }}/{{ $totalEnrolled }} tugas</span>
            @endif
        </div>

        {{-- Clickable title → detail page with absensi --}}
        <a href="{{ url('/pengajar/pertemuan/' . $item->id) }}" class="timeline-title" style="text-decoration:none; color: var(--on-surface); display: block;">
            {{ $item->judul }}
            <svg width="14" height="14" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-left: 4px;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        @if($item->deskripsi)
            <div class="timeline-body">{{ Str::limit($item->deskripsi, 80) }}</div>
        @endif
        @if($item->isTugas() && $item->deadline)
            <div class="timeline-body mt-xs"><strong>Deadline:</strong> {{ $item->deadline->format('d M Y H:i') }}</div>
        @endif
        @if($item->materiFiles->count())
            <div class="timeline-files">
                @foreach($item->materiFiles as $file)
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="timeline-file">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    {{ $file->file_name }}
                </a>
                @endforeach
            </div>
        @endif

        <div class="d-flex gap-sm mt-sm align-center">
            <a href="{{ url('/pengajar/pertemuan/' . $item->id) }}" class="btn btn-outline btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Absensi & Detail
            </a>
            <form method="POST" action="{{ url('/pengajar/pertemuan/' . $item->id) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm" data-confirm="Hapus {{ $item->isTugas() ? 'tugas' : 'pertemuan' }} ini?" style="color:var(--error);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </li>
    @endforeach
</ul>
@else
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    <h3>Belum Ada Pertemuan</h3>
    <p>Tambahkan pertemuan atau tugas pertama untuk kelas ini.</p>
</div>
@endif

{{-- Modal: Tambah Pertemuan --}}
<div class="modal-overlay" id="modal-add-pertemuan">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Pertemuan / Tugas</h3>
            <button class="modal-close" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ url('/pengajar/kelas/' . $kelas->id . '/pertemuan') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tipe <span class="required">✱</span></label>
                    <select class="form-control" name="tipe" id="pertemuan-tipe" required>
                        <option value="pertemuan">Pertemuan</option>
                        <option value="tugas">Tugas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul <span class="required">✱</span></label>
                    <input type="text" class="form-control" name="judul" placeholder="Judul pertemuan/tugas" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">✱</span></label>
                    <input type="date" class="form-control" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" placeholder="Deskripsi singkat..." style="min-height:80px;"></textarea>
                </div>
                <div id="tugas-fields" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instruksi Tugas</label>
                        <textarea class="form-control" name="instruksi_tugas" placeholder="Instruksi pengerjaan tugas..." style="min-height:80px;"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Upload Materi (Opsional)</label>
                    <input type="file" class="form-file" name="files[]" multiple>
                    <span class="form-text">Bisa upload banyak file. Maks 10MB per file.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('pertemuan-tipe').addEventListener('change', function() {
    document.getElementById('tugas-fields').style.display = this.value === 'tugas' ? 'block' : 'none';
});
</script>
@endpush
@endsection
