@extends('layouts.dashboard')
@section('title', $kui->judul)

@php const KKM = 75; @endphp

@section('content')
<div class="mb-lg">
    <a href="{{ route('pengajar.kelas.show', $kelas) }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke {{ $kelas->nama_kelas }}
    </a>
    <div class="d-flex justify-between align-center flex-wrap gap-md">
        <div>
            <h1 class="headline-lg">{{ $kui->judul }}</h1>
            <p class="text-muted">{{ $kui->deskripsi }}</p>
        </div>
        <div class="d-flex gap-sm flex-wrap">
            <form method="POST" action="{{ route('pengajar.kuis.toggle', $kui) }}" style="display:inline;">
                @csrf @method('PUT')
                <button type="submit" class="btn {{ $kui->is_active ? 'btn-outline' : 'btn-primary' }} btn-sm">
                    {{ $kui->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <form method="POST" action="{{ route('pengajar.kuis.destroy', $kui) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus kuis {{ $kui->judul }}?">Hapus Kuis</button>
            </form>
        </div>
    </div>
</div>

{{-- Kuis Stats --}}
@php
    $completedResults = $kui->hasil->where('waktu_selesai', '!=', null);
    $lulusCount = $completedResults->where('nilai', '>=', KKM)->count();
    $tidakLulusCount = $completedResults->where('nilai', '<', KKM)->count();
    $avgNilai = $completedResults->count() > 0 ? round($completedResults->avg('nilai'), 1) : 0;
@endphp
<div class="stats-grid mb-lg">
    <div class="stat-card">
        <div class="stat-value">{{ $kui->soal->count() }}</div>
        <div class="stat-label">Total Soal</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $kui->soal->where('tipe', 'pilihan_ganda')->count() }}</div>
        <div class="stat-label">Pilihan Ganda</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $kui->soal->where('tipe', 'essay')->count() }}</div>
        <div class="stat-label">Essay</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $completedResults->count() }}/{{ $enrolledCount }}</div>
        <div class="stat-label">Dikerjakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:var(--secondary);">{{ $lulusCount }}</div>
        <div class="stat-label">✅ Lulus (≥{{ KKM }})</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:var(--error);">{{ $tidakLulusCount }}</div>
        <div class="stat-label">❌ Tidak Lulus</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $avgNilai }}</div>
        <div class="stat-label">Rata-rata Nilai</div>
    </div>
</div>

{{-- Info badges --}}
<div class="d-flex gap-sm flex-wrap mb-lg">
    <span class="badge badge-primary">Durasi: {{ $kui->durasi_menit }} menit</span>
    <span class="badge badge-warning">KKM: {{ KKM }}</span>

    @if($kui->deadline)
        <span class="badge {{ $kui->isExpired() ? 'badge-danger' : 'badge-warning' }}">
            Deadline: {{ $kui->deadline->format('d M Y H:i') }}
        </span>
    @endif
    <span class="badge {{ $kui->is_active ? 'badge-success' : 'badge-neutral' }}">
        {{ $kui->is_active ? 'Aktif' : 'Nonaktif' }}
    </span>
    @if($kui->acak_soal)
        <span class="badge badge-neutral">Urutan Soal Diacak</span>
    @endif
</div>

{{-- Soal List --}}
<div class="card mb-lg" style="cursor:default;">
    <div class="card-header"><h3 class="headline-sm">Daftar Soal</h3></div>
    <div class="card-body" style="padding:0;">
        @foreach($kui->soal as $soal)
        <div class="deadline-item" style="flex-direction:column; align-items:flex-start;">
            <div class="d-flex align-center gap-sm mb-xs" style="width:100%;">
                <span class="badge {{ $soal->isPilihanGanda() ? 'badge-primary' : 'badge-warning' }}" style="font-size:10px;">
                    {{ $soal->isPilihanGanda() ? 'PG' : 'Essay' }}
                </span>
                <strong class="body-sm">Soal #{{ $soal->nomor }}</strong>
                @if($soal->isPilihanGanda())
                    @php
                        $opsiCount = 0;
                        foreach(['opsi_a','opsi_b','opsi_c','opsi_d','opsi_e'] as $f) { if($soal->$f) $opsiCount++; }
                    @endphp
                    <span class="body-xs text-muted">{{ $opsiCount }} opsi</span>
                @endif
            </div>
            <p class="body-sm" style="margin:0;">{{ $soal->pertanyaan }}</p>
            @if($soal->isPilihanGanda())
                <div class="body-xs text-muted mt-xs" style="padding-left:8px;">
                    @foreach(['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e'] as $letter => $field)
                        @if($soal->$field)
                            <div style="{{ $soal->jawaban_benar === $letter ? 'color:var(--secondary); font-weight:600;' : '' }}">
                                {{ $letter }}. {{ $soal->$field }} {!! $soal->jawaban_benar === $letter ? ' ✓' : '' !!}
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- Hasil Mahasiswa --}}
<h2 class="headline-md mb-md">Hasil Mahasiswa</h2>

@if($kui->hasil->count())
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mahasiswa</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Benar / Total</th>
                <th>Nilai</th>
                <th>KKM ({{ KKM }})</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kui->hasil->sortByDesc('nilai') as $idx => $h)
            @php $lulus = $h->nilai !== null && $h->nilai >= KKM; @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <div class="d-flex align-center gap-sm">
                        <img src="{{ $h->mahasiswa->foto_profile ? asset('storage/'.$h->mahasiswa->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($h->mahasiswa->nama).'&size=32&background=cce5ff&color=004b73' }}" class="avatar" style="width:28px;height:28px;">
                        {{ $h->mahasiswa->nama }}
                    </div>
                </td>
                <td class="body-sm">{{ $h->waktu_mulai?->format('d/m H:i') ?? '-' }}</td>
                <td class="body-sm">{{ $h->waktu_selesai?->format('d/m H:i') ?? '-' }}</td>
                <td><strong>{{ $h->total_benar }}</strong> / {{ $h->max_poin }}</td>
                <td>
                    <strong style="font-size:var(--font-size-md); color:{{ $h->nilai !== null && $h->nilai >= KKM ? 'var(--secondary)' : 'var(--error)' }};">
                        {{ $h->nilai ?? '-' }}
                    </strong>
                </td>
                <td>
                    @if($h->isCompleted())
                        @if($lulus)
                            <span class="badge badge-success">✅ Lulus</span>
                        @else
                            <span class="badge badge-danger">❌ Tidak Lulus</span>
                        @endif
                    @else
                        <span class="badge badge-neutral">-</span>
                    @endif
                </td>
                <td>
                    @if($h->isCompleted())
                        @php
                            $hasUngradedEssay = $h->jawaban->contains(fn($j) => $j->soal->isEssay() && $j->is_correct === null);
                        @endphp
                        @if($hasUngradedEssay)
                            <span class="badge badge-warning">Perlu Dinilai</span>
                        @else
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    @else
                        <span class="badge badge-neutral">Sedang Mengerjakan</span>
                    @endif
                </td>
            </tr>

            {{-- Essay Grading inline --}}
            @php $ungradedEssays = $h->jawaban->filter(fn($j) => $j->soal->isEssay() && $j->is_correct === null); @endphp
            @if($ungradedEssays->count() && $h->isCompleted())
            <tr>
                <td colspan="8" style="background:var(--surface-container-low); padding:var(--space-md);">
                    <form method="POST" action="{{ route('pengajar.kuis.nilai-essay', $kui) }}">
                        @csrf
                        <strong class="body-sm d-block mb-sm" style="color:var(--tertiary);">Nilai Essay — {{ $h->mahasiswa->nama }}</strong>
                        @foreach($ungradedEssays as $jawaban)
                        <div class="mb-md" style="padding-left:var(--space-sm); border-left:3px solid var(--tertiary-container);">
                            <div class="body-xs text-muted mb-xs">Soal #{{ $jawaban->soal->nomor }}: {{ Str::limit($jawaban->soal->pertanyaan, 80) }}</div>
                            <div class="body-sm mb-xs"><strong>Jawaban:</strong> {{ $jawaban->jawaban ?: '(tidak dijawab)' }}</div>
                            @if($jawaban->soal->jawaban_benar)
                                <div class="body-xs text-muted mb-xs"><em>Kunci: {{ Str::limit($jawaban->soal->jawaban_benar, 100) }}</em></div>
                            @endif
                            <div class="d-flex align-center gap-sm">
                                <input type="hidden" name="penilaian[{{ $jawaban->id }}][jawaban_id]" value="{{ $jawaban->id }}">
                                <label class="body-xs">Benar?</label>
                                <select class="form-control" name="penilaian[{{ $jawaban->id }}][benar]" required style="width:120px;">
                                    <option value="">-- Pilih --</option>
                                    <option value="1">✓ Benar</option>
                                    <option value="0">✗ Salah</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary btn-sm mt-sm">Simpan Penilaian Essay</button>
                    </form>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
    <h3>Belum Ada Mahasiswa Mengerjakan</h3>
    <p>Hasil akan muncul setelah mahasiswa menyelesaikan kuis.</p>
</div>
@endif
@endsection
