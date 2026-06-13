@extends('layouts.dashboard')
@section('title', $kelas->nama_kelas)

@section('content')
<a href="{{ url('/mahasiswa/kelas') }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke Kelas Saya
</a>

<div class="d-flex justify-between align-start flex-wrap gap-md mb-xl">
    <div style="flex:1;min-width:0;">
        <h1 class="headline-lg">{{ $kelas->nama_kelas }}</h1>
        <p class="text-muted mt-sm">{{ $kelas->deskripsi }}</p>
        <div class="d-flex align-center gap-sm mt-md">
            <img src="{{ $kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=40&background=cce5ff&color=004b73' }}" alt="" class="avatar" style="width:36px;height:36px;">
            <div>
                <div class="body-sm fw-600">{{ $kelas->pengajar->nama }}</div>
                <div class="body-sm text-muted">Pengajar</div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline --}}
<h2 class="headline-md mb-lg">Jadwal Pertemuan & Tugas</h2>

@if($kelas->pertemuan->count())
<ul class="timeline">
    @foreach($kelas->pertemuan as $item)
    <li class="timeline-item {{ $item->isTugas() ? 'task' : '' }} {{ isset($submissions[$item->id]) ? 'completed' : '' }}">
        <div class="timeline-date">
            {{ $item->tanggal->format('d M Y') }}
            · <span class="badge {{ $item->isTugas() ? 'badge-warning' : 'badge-primary' }}">{{ $item->isTugas() ? 'Tugas' : 'Pertemuan' }}</span>
            @if($item->isTugas() && isset($submissions[$item->id]))
                <span class="badge badge-success">Dikumpulkan</span>
            @endif
            {{-- Absensi Status --}}
            @if(isset($absensiMap[$item->id]))
                @php $absensiStatus = $absensiMap[$item->id]->status; @endphp
                @if($absensiStatus === 'hadir')
                    <span class="badge badge-success">✅ Hadir</span>
                @elseif($absensiStatus === 'izin')
                    <span class="badge badge-warning">📝 Izin</span>
                @else
                    <span class="badge badge-error">❌ Tidak Hadir</span>
                @endif
            @endif
        </div>

        {{-- Clickable title linking to detail page --}}
        <a href="{{ url('/mahasiswa/pertemuan/' . $item->id) }}" class="timeline-title" style="text-decoration:none; color: var(--on-surface); display: block;">
            {{ $item->judul }}
            <svg width="14" height="14" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-left: 4px;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        @if($item->deskripsi)
            <div class="timeline-body">{{ Str::limit($item->deskripsi, 80) }}</div>
        @endif

        {{-- Quick info badges --}}
        <div class="d-flex align-center gap-xs flex-wrap mt-xs">
            @if($item->materiFiles->count())
                <span class="badge badge-neutral body-sm">📎 {{ $item->materiFiles->count() }} file</span>
            @endif
            @if($item->isTugas() && $item->deadline)
                <span class="badge {{ now()->gt($item->deadline) ? 'badge-error' : 'badge-neutral' }} body-sm">
                    ⏰ {{ $item->deadline->format('d M H:i') }}
                </span>
            @endif
            @if($item->isTugas() && isset($submissions[$item->id]) && $submissions[$item->id]->nilai !== null)
                <span class="badge badge-primary body-sm">Nilai: {{ $submissions[$item->id]->nilai }}</span>
            @endif
        </div>
    </li>
    @endforeach
</ul>
@else
<div class="empty-state">
    <p>Belum ada pertemuan untuk kelas ini.</p>
</div>
@endif

{{-- Kuis Section --}}
@if(isset($kuisList) && $kuisList->count())
<h2 class="headline-md mb-lg mt-xl">Kuis</h2>
<div class="d-flex gap-md flex-wrap">
    @foreach($kuisList as $kuis)
    @php
        $hasilKuis = $kuis->hasil->where('mahasiswa_id', auth()->id())->first();
        $isCompleted = $hasilKuis && $hasilKuis->isCompleted();
    @endphp
    <div class="card" style="flex:1; min-width:260px; max-width:380px; cursor:default; border-top:3px solid {{ $isCompleted ? 'var(--secondary)' : 'var(--primary)' }};">
        <div class="card-body">
            <div class="d-flex justify-between align-center mb-sm">
                <h4 class="headline-sm" style="margin:0;">{{ $kuis->judul }}</h4>
                @if($isCompleted)
                    <span class="badge badge-success">Selesai</span>
                @elseif($kuis->isExpired())
                    <span class="badge badge-danger">Expired</span>
                @else
                    <span class="badge badge-primary">Tersedia</span>
                @endif
            </div>
            @if($kuis->deskripsi)
                <p class="body-sm text-muted mb-sm">{{ Str::limit($kuis->deskripsi, 60) }}</p>
            @endif
            <div class="d-flex gap-sm flex-wrap mb-sm">
                <span class="badge badge-neutral">{{ $kuis->soal->count() }} soal</span>
                <span class="badge badge-neutral">{{ $kuis->durasi_menit }} mnt</span>
                @if($kuis->deadline)
                    <span class="badge {{ $kuis->isExpired() ? 'badge-danger' : 'badge-warning' }}">{{ $kuis->deadline->format('d M H:i') }}</span>
                @endif
            </div>
            @if($isCompleted)
                @php $lulus = $hasilKuis->nilai >= 75; @endphp
                <div class="body-sm mb-xs" style="color:{{ $lulus ? 'var(--secondary)' : 'var(--error)' }};">
                    <strong>Nilai: {{ $hasilKuis->nilai }}</strong> · {{ $hasilKuis->total_benar }}/{{ $hasilKuis->max_poin }} benar
                </div>
                <div class="mb-sm">
                    <span class="badge {{ $lulus ? 'badge-success' : 'badge-danger' }}">
                        {{ $lulus ? '✅ Lulus KKM' : '❌ Tidak Lulus KKM' }}
                    </span>
                </div>
                <a href="{{ route('mahasiswa.kuis.hasil', $kuis) }}" class="btn btn-outline btn-sm">Lihat Hasil</a>
            @elseif(!$kuis->isExpired())
                <a href="{{ route('mahasiswa.kuis.show', $kuis) }}" class="btn btn-primary btn-sm">Kerjakan Kuis</a>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
