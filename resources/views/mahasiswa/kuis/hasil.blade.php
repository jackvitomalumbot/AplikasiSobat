@extends('layouts.dashboard')
@section('title', 'Hasil Kuis — ' . $kui->judul)

@section('content')
<div class="mb-lg">
    <a href="{{ route('mahasiswa.kelas.show', $kelas) }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke {{ $kelas->nama_kelas }}
    </a>
    <h1 class="headline-lg">Hasil: {{ $kui->judul }}</h1>
</div>

{{-- Score Card --}}
@php
    $lulus = $hasil->nilai >= $kkm;
    $scoreColor = $lulus ? 'var(--secondary)' : 'var(--error)';
@endphp
<div class="card mb-lg" style="cursor:default; text-align:center; border-top:4px solid {{ $scoreColor }};">
    <div class="card-body" style="padding:var(--space-xl);">
        {{-- Big Score --}}
        <div style="font-size:4rem; font-weight:800; color:{{ $scoreColor }}; line-height:1;">
            {{ $hasil->nilai ?? '—' }}
        </div>
        <p class="text-muted mb-sm" style="font-size:var(--font-size-md);">Nilai Anda</p>

        {{-- KKM Status --}}
        <div style="display:inline-block; padding:8px 24px; border-radius:var(--radius-full); font-weight:700; font-size:var(--font-size-md); margin-bottom:var(--space-lg);
            background:{{ $lulus ? '#dcfce7' : 'var(--error-container)' }};
            color:{{ $lulus ? '#15803d' : 'var(--on-error-container)' }};">
            {{ $lulus ? '✅ LULUS — KKM Tercapai' : '❌ TIDAK LULUS — KKM Belum Tercapai' }}
        </div>

        <p class="body-sm text-muted mb-md">KKM (Kriteria Ketuntasan Minimal): <strong>{{ $kkm }}</strong></p>

        {{-- Stats --}}
        <div class="d-flex justify-center gap-lg flex-wrap">
            <div>
                <div class="headline-sm">{{ $hasil->total_benar }}</div>
                <div class="body-xs text-muted">Jawaban Benar</div>
            </div>
            <div>
                <div class="headline-sm">{{ $hasil->max_poin - $hasil->total_benar }}</div>
                <div class="body-xs text-muted">Jawaban Salah</div>
            </div>
            <div>
                <div class="headline-sm">{{ $hasil->max_poin }}</div>
                <div class="body-xs text-muted">Total Soal</div>
            </div>
            @if($hasil->waktu_mulai && $hasil->waktu_selesai)
            @php
                $detik = $hasil->waktu_mulai->diffInSeconds($hasil->waktu_selesai);
                if ($detik < 60) {
                    $waktuLabel = '< 1 menit';
                } elseif ($detik < 3600) {
                    $waktuLabel = floor($detik / 60) . ' menit';
                } else {
                    $jam = floor($detik / 3600);
                    $mnt = floor(($detik % 3600) / 60);
                    $waktuLabel = $jam . ' jam ' . ($mnt > 0 ? $mnt . ' menit' : '');
                }
            @endphp
            <div>
                <div class="headline-sm">{{ $waktuLabel }}</div>
                <div class="body-xs text-muted">Waktu Pengerjaan</div>
            </div>
            @endif
        </div>



        @php $hasUngradedEssay = $hasil->jawaban->contains(fn($j) => $j->soal->isEssay() && $j->is_correct === null); @endphp
        @if($hasUngradedEssay)
            <div class="mt-md" style="padding:10px 16px; border-radius:var(--radius-sm); background:var(--tertiary-container); color:var(--on-tertiary-container); display:inline-block;">
                ⏳ Beberapa soal essay belum dinilai oleh pengajar. Nilai dapat berubah setelah penilaian essay.
            </div>
        @endif
    </div>
</div>

{{-- Review Jawaban --}}
<h2 class="headline-md mb-md">Review Jawaban</h2>

@php $jawabanMap = $hasil->jawaban->keyBy('kuis_soal_id'); @endphp

@foreach($kui->soal as $idx => $soal)
@php $jwb = $jawabanMap[$soal->id] ?? null; @endphp
<div class="card mb-md" style="cursor:default; border-left:3px solid {{ $jwb && $jwb->is_correct === true ? 'var(--secondary)' : ($jwb && $jwb->is_correct === false ? 'var(--error)' : 'var(--outline-variant)') }};">
    <div class="card-body">
        <div class="d-flex align-center gap-sm mb-sm">
            <span class="badge {{ $soal->isPilihanGanda() ? 'badge-primary' : 'badge-warning' }}" style="font-size:10px;">
                {{ $soal->isPilihanGanda() ? 'PG' : 'Essay' }}
            </span>
            <strong>Soal {{ $soal->nomor }}</strong>
            @if($jwb)
                @if($jwb->is_correct === true)
                    <span class="badge badge-success">✓ Benar</span>
                @elseif($jwb->is_correct === false)
                    <span class="badge badge-danger">✗ Salah</span>
                @else
                    <span class="badge badge-neutral">Belum dinilai</span>
                @endif
            @else
                <span class="badge badge-neutral">Tidak dijawab</span>
            @endif
        </div>

        <p class="body-md mb-sm" style="white-space:pre-line;">{{ $soal->pertanyaan }}</p>

        @if($soal->isPilihanGanda())
            @foreach(['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e'] as $letter => $field)
                @if($soal->$field)
                @php
                    $isUserAnswer = $jwb && strtoupper($jwb->jawaban) === $letter;
                    $isCorrect = strtoupper($soal->jawaban_benar) === $letter;
                @endphp
                <div class="d-flex align-center gap-sm mb-xs body-sm" style="padding:6px 10px; border-radius:var(--radius-sm); {{ $isCorrect ? 'background:rgba(21,128,61,0.1); color:#15803d; font-weight:600;' : ($isUserAnswer && !$isCorrect ? 'background:rgba(186,26,26,0.1); color:#ba1a1a;' : '') }}">
                    <strong style="min-width:20px;">{{ $letter }}.</strong>
                    <span>{{ $soal->$field }}</span>
                    @if($isCorrect) <span style="margin-left:auto;">✓ Jawaban benar</span> @endif
                    @if($isUserAnswer && !$isCorrect) <span style="margin-left:auto;">← Jawaban Anda</span> @endif
                    @if($isUserAnswer && $isCorrect) <span style="margin-left:auto;">✓ Jawaban Anda</span> @endif
                </div>
                @endif
            @endforeach
        @else
            <div style="padding:10px 14px; border-radius:var(--radius-sm); background:var(--surface-container); margin-top:8px;">
                <div class="body-xs text-muted mb-xs">Jawaban Anda:</div>
                <p class="body-sm">{{ $jwb->jawaban ?? '(tidak dijawab)' }}</p>
            </div>
        @endif
    </div>
</div>
@endforeach
@endsection
