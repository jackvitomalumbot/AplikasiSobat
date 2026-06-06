@extends('layouts.dashboard')

@section('title', $pertemuan->judul)

@section('content')
<div class="page-header">
    <div class="d-flex justify-between align-center flex-wrap gap-md">
        <div>
            <a href="{{ url('/pengajar/kelas/' . $kelas->id) }}" class="btn btn-ghost btn-sm mb-sm" style="margin-left: -8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke {{ $kelas->nama_kelas }}
            </a>
            <h1>{{ $pertemuan->judul }}</h1>
            <p>
                <span class="badge {{ $pertemuan->isTugas() ? 'badge-warning' : 'badge-primary' }}">{{ ucfirst($pertemuan->tipe) }}</span>
                <span class="text-muted" style="margin-left: 8px;">{{ $pertemuan->tanggal->translatedFormat('d F Y') }}</span>
                @if($pertemuan->isTugas() && $pertemuan->deadline)
                    <span class="text-muted" style="margin-left: 8px;">• Deadline: {{ $pertemuan->deadline->translatedFormat('d M Y H:i') }}</span>
                @endif
            </p>
        </div>
        <form method="POST" action="{{ route('pengajar.pertemuan.destroy', $pertemuan) }}" onsubmit="return confirm('Yakin hapus pertemuan/tugas ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                Hapus
            </button>
        </form>
    </div>
</div>

{{-- Detail Info --}}
<div class="grid grid-2" style="margin-bottom: var(--space-xl);">
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">Detail</h3>
            @if($pertemuan->deskripsi)
                <p class="mb-md">{{ $pertemuan->deskripsi }}</p>
            @endif
            @if($pertemuan->isTugas() && $pertemuan->instruksi_tugas)
                <div class="mb-md">
                    <span class="fw-600 body-sm">Instruksi Tugas:</span>
                    <p class="mt-xs">{{ $pertemuan->instruksi_tugas }}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">File Materi</h3>
            @if($pertemuan->materiFiles->count() > 0)
                <div class="timeline-files">
                    @foreach($pertemuan->materiFiles as $file)
                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="timeline-file">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            {{ $file->file_name }}
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted body-sm">Belum ada file materi.</p>
            @endif
        </div>
    </div>
</div>

{{-- Absensi Section --}}
<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-header d-flex justify-between align-center">
        <h3 class="headline-sm">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Absensi Mahasiswa
        </h3>
        <span class="badge badge-neutral">{{ $enrolledMahasiswa->count() }} mahasiswa</span>
    </div>
    <div class="card-body">
        @if($enrolledMahasiswa->count() > 0)
            <form method="POST" action="{{ route('pengajar.absensi.store', $pertemuan) }}">
                @csrf
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Email</th>
                                <th style="text-align: center;">Hadir</th>
                                <th style="text-align: center;">Tidak Hadir</th>
                                <th style="text-align: center;">Izin</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrolledMahasiswa as $idx => $mhs)
                                @php
                                    $current = $absensiMap[$mhs->id] ?? null;
                                    $currentStatus = $current ? $current->status : 'tidak_hadir';
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-center gap-xs">
                                            <img src="{{ $mhs->foto_profile ? asset('storage/' . $mhs->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($mhs->nama) . '&size=32&background=cce5ff&color=004b73' }}" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                            {{ $mhs->nama }}
                                        </div>
                                    </td>
                                    <td class="text-muted body-sm">{{ $mhs->email }}</td>
                                    <td style="text-align:center;">
                                        <input type="hidden" name="absensi[{{ $idx }}][mahasiswa_id]" value="{{ $mhs->id }}">
                                        <input type="radio" name="absensi[{{ $idx }}][status]" value="hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--secondary);">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="radio" name="absensi[{{ $idx }}][status]" value="tidak_hadir" {{ $currentStatus === 'tidak_hadir' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--error);">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="radio" name="absensi[{{ $idx }}][status]" value="izin" {{ $currentStatus === 'izin' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--tertiary);">
                                    </td>
                                    <td>
                                        <input type="text" name="absensi[{{ $idx }}][keterangan]" value="{{ $current->keterangan ?? '' }}" class="form-control" placeholder="Opsional" style="padding:6px 10px;font-size:12px;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: var(--space-lg); text-align: right;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                        Simpan Absensi
                    </button>
                </div>
            </form>
        @else
            <div class="empty-state">
                <p>Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- Tugas Submissions (only show for tugas type) --}}
@if($pertemuan->isTugas())
<div class="card">
    <div class="card-header d-flex justify-between align-center">
        <h3 class="headline-sm">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Pengumpulan Tugas
        </h3>
        <span class="badge badge-neutral">{{ $submissionMap->count() }}/{{ $enrolledMahasiswa->count() }} dikumpulkan</span>
    </div>
    <div class="card-body">
        @if($enrolledMahasiswa->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Catatan</th>
                            <th>Dikumpulkan</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledMahasiswa as $idx => $mhs)
                            @php $sub = $submissionMap[$mhs->id] ?? null; @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>
                                    @if($sub)
                                        <span class="badge badge-success">Sudah</span>
                                    @else
                                        <span class="badge badge-error">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sub)
                                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="timeline-file" style="display:inline-flex;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                            Download
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="body-sm">{{ $sub->catatan ?? '-' }}</td>
                                <td class="body-sm text-muted">{{ $sub ? $sub->created_at->translatedFormat('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if($sub)
                                        <span class="fw-600" style="color: var(--primary);">{{ $sub->nilai ?? '-' }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($sub)
                                        <form method="POST" action="{{ route('pengajar.nilai.store', $pertemuan) }}" class="d-inline-flex gap-xs align-center">
                                            @csrf
                                            <input type="hidden" name="submission_id" value="{{ $sub->id }}">
                                            <input type="number" name="nilai" value="{{ $sub->nilai }}" min="0" max="100" class="form-control" style="width:70px;padding:4px 8px;font-size:12px;" placeholder="0-100">
                                            <button type="submit" class="btn btn-primary btn-sm" style="padding:4px 10px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            </div>
        @endif
    </div>
</div>
@endif
@endsection
