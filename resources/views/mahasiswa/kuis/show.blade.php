@extends('layouts.dashboard')
@section('title', $kui->judul)

@section('content')
<div class="mb-lg">
    <a href="{{ route('mahasiswa.kelas.show', $kelas) }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke {{ $kelas->nama_kelas }}
    </a>
    <h1 class="headline-lg">{{ $kui->judul }}</h1>
    @if($kui->deskripsi)
        <p class="text-muted">{{ $kui->deskripsi }}</p>
    @endif
</div>

{{-- Timer & Info --}}
<div class="card mb-lg" style="cursor:default; border-left:3px solid var(--primary);">
    <div class="card-body d-flex justify-between align-center flex-wrap gap-md">
        <div>
            <span class="badge badge-primary">{{ $soal->count() }} Soal</span>
            <span class="badge badge-neutral">Durasi: {{ $kui->durasi_menit }} menit</span>
            <span class="badge badge-warning">KKM: 75</span>
            @if($kui->deadline)
                <span class="badge badge-warning">Deadline: {{ $kui->deadline->format('d M Y H:i') }}</span>
            @endif
        </div>
        <div id="timer" class="headline-md" style="color:var(--primary); font-weight:800; font-variant-numeric:tabular-nums;"></div>
    </div>
</div>

<form method="POST" action="{{ route('mahasiswa.kuis.submit', $kui) }}" id="form-kuis-submit" onsubmit="return confirmSubmit();">
    @csrf

    @foreach($soal as $idx => $s)
    <div class="card mb-md" style="cursor:default; border-left:3px solid var(--{{ $s->isPilihanGanda() ? 'primary' : 'tertiary' }});">
        <div class="card-body">
            <div class="d-flex align-center gap-sm mb-sm">
                <span class="badge {{ $s->isPilihanGanda() ? 'badge-primary' : 'badge-warning' }}" style="font-size:10px;">
                    {{ $s->isPilihanGanda() ? 'Pilihan Ganda' : 'Essay' }}
                </span>
                <strong>Soal {{ $idx + 1 }}</strong>
            </div>

            <p class="body-md mb-md" style="white-space:pre-line;">{{ $s->pertanyaan }}</p>

            @if($s->isPilihanGanda())
                @foreach(['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e'] as $letter => $field)
                    @if($s->$field)
                    <label class="d-flex align-center gap-sm mb-xs" style="cursor:pointer; padding:8px 12px; border-radius:var(--radius-sm); border:1px solid var(--outline-variant); transition:all 0.15s;">
                        <input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $letter }}" style="accent-color:var(--primary);">
                        <strong style="min-width:20px;">{{ $letter }}.</strong>
                        <span>{{ $s->$field }}</span>
                    </label>
                    @endif
                @endforeach
            @else
                <textarea class="form-control" name="jawaban[{{ $s->id }}]" placeholder="Tulis jawaban Anda di sini..." style="min-height:100px;"></textarea>
            @endif
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-between align-center mt-lg">
        <p class="text-muted body-sm">Pastikan semua soal sudah dijawab sebelum mengumpulkan.</p>
        <button type="submit" class="btn btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Kumpulkan Kuis
        </button>
    </div>
</form>

@push('scripts')
<script>
// Timer
const startTime = new Date('{{ $hasil->waktu_mulai->toIso8601String() }}').getTime();
const durasiMs = {{ $kui->durasi_menit }} * 60 * 1000;
const endTime = startTime + durasiMs;
const timerEl = document.getElementById('timer');

function updateTimer() {
    const now = Date.now();
    const remaining = endTime - now;

    if (remaining <= 0) {
        timerEl.textContent = '00:00:00';
        timerEl.style.color = 'var(--error)';
        document.getElementById('form-kuis-submit').submit();
        return;
    }

    const hrs = Math.floor(remaining / 3600000);
    const mins = Math.floor((remaining % 3600000) / 60000);
    const secs = Math.floor((remaining % 60000) / 1000);

    timerEl.textContent = String(hrs).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

    if (remaining < 60000) {
        timerEl.style.color = 'var(--error)';
        timerEl.style.animation = 'pulse 0.5s ease-in-out infinite alternate';
    } else if (remaining < 300000) {
        timerEl.style.color = 'var(--tertiary)';
    }

    requestAnimationFrame(updateTimer);
}
updateTimer();

// Radio highlight
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        this.closest('.card-body').querySelectorAll('label').forEach(l => {
            l.style.background = '';
            l.style.borderColor = 'var(--outline-variant)';
        });
        this.closest('label').style.background = 'var(--primary-container)';
        this.closest('label').style.borderColor = 'var(--primary)';
    });
});

function confirmSubmit() {
    return confirm('Yakin ingin mengumpulkan kuis? Jawaban tidak dapat diubah setelah dikumpulkan.');
}
</script>
@endpush
@endsection
