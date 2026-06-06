@extends('layouts.dashboard')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="page-header">
    <h1>Halo, {{ auth()->user()->nama }}! 👋</h1>
    <p>Berikut ringkasan tugas dan deadline kamu</p>
</div>

{{-- Upcoming Deadlines --}}
<div class="card mb-xl" style="cursor:default;">
    <div class="card-header d-flex justify-between align-center">
        <h2 class="headline-sm">Deadline Mendatang</h2>
        <span class="badge badge-warning">{{ $deadlines->count() }} tugas</span>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($deadlines as $deadline)
        <div class="deadline-item">
            @php
                $daysLeft = now()->diffInDays($deadline->deadline, false);
                $iconClass = $daysLeft <= 2 ? 'urgent' : ($daysLeft <= 7 ? 'normal' : 'done');
            @endphp
            <div class="deadline-icon {{ $iconClass }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="deadline-content">
                <div class="deadline-title">{{ $deadline->judul }}</div>
                <div class="deadline-meta">{{ $deadline->kelas->nama_kelas }} · Deadline: {{ $deadline->deadline->format('d M Y, H:i') }}</div>
            </div>
            <div class="deadline-badge">
                @if($daysLeft <= 0)
                    <span class="badge badge-error">Overdue</span>
                @elseif($daysLeft <= 2)
                    <span class="badge badge-warning">{{ $daysLeft }} hari lagi</span>
                @else
                    <span class="badge badge-primary">{{ $daysLeft }} hari lagi</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-muted" style="padding:var(--space-xl);">Tidak ada deadline mendatang. 🎉</p>
        @endforelse
    </div>
</div>

{{-- All Tasks --}}
<h2 class="headline-md mb-lg">Semua Tugas</h2>

@forelse($allTugas as $tugas)
<div class="card mb-md" style="cursor:default;">
    <div class="card-body d-flex justify-between align-center gap-md flex-wrap">
        <div style="flex:1;min-width:0;">
            <div class="d-flex align-center gap-sm mb-xs">
                <span class="badge {{ $tugas->tugasSubmissions->count() ? 'badge-success' : 'badge-warning' }}">
                    {{ $tugas->tugasSubmissions->count() ? 'Sudah Dikumpulkan' : 'Belum Dikumpulkan' }}
                </span>
            </div>
            <h3 class="headline-sm" style="font-size:16px;">{{ $tugas->judul }}</h3>
            <p class="body-sm text-muted">{{ $tugas->kelas->nama_kelas }} · Deadline: {{ $tugas->deadline?->format('d M Y, H:i') ?? '-' }}</p>
            @if($tugas->tugasSubmissions->first()?->nilai !== null)
                <p class="body-sm mt-xs"><strong>Nilai:</strong> {{ $tugas->tugasSubmissions->first()->nilai }}</p>
            @endif
        </div>
        <a href="{{ url('/mahasiswa/kelas/' . $tugas->kelas_id) }}" class="btn btn-outline btn-sm">Lihat Kelas</a>
    </div>
</div>
@empty
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    <h3>Belum Ada Tugas</h3>
    <p>Bergabung dengan kelas untuk mulai menerima tugas.</p>
    <a href="{{ url('/mahasiswa/beli-kelas') }}" class="btn btn-primary">Cari Kelas</a>
</div>
@endforelse
@endsection
