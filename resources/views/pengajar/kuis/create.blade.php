@extends('layouts.dashboard')
@section('title', 'Buat Kuis — ' . $kelas->nama_kelas)

@section('content')
<div class="mb-lg">
    <a href="{{ route('pengajar.kelas.show', $kelas) }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke {{ $kelas->nama_kelas }}
    </a>
    <h1 class="headline-lg">Buat Kuis Baru</h1>
    <p class="text-muted">Buat soal pilihan ganda dan/atau essay. Maksimal 100 soal per kuis.</p>
    <div class="d-flex gap-sm mt-sm">
        <span class="badge badge-primary">KKM: 75</span>
    </div>
</div>

<form method="POST" action="{{ route('pengajar.kuis.store', $kelas) }}" id="form-kuis">
    @csrf

    {{-- Kuis Info --}}
    <div class="card mb-lg" style="cursor:default;">
        <div class="card-header"><h3 class="headline-sm">Informasi Kuis</h3></div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Judul Kuis <span class="required">✱</span></label>
                <input type="text" class="form-control" name="judul" placeholder="Contoh: Kuis Anatomi Minggu 3" required>
            </div>
            <div class="d-flex gap-md flex-wrap">
                <div class="form-group" style="flex:1; min-width:200px;">
                    <label class="form-label">Durasi (menit) <span class="required">✱</span></label>
                    <input type="number" class="form-control" name="durasi_menit" value="60" min="1" max="600" required>
                </div>
                <div class="form-group" style="flex:1; min-width:200px;">
                    <label class="form-label">Deadline</label>
                    <input type="datetime-local" class="form-control" name="deadline">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" placeholder="Instruksi atau deskripsi kuis..." style="min-height:70px;"></textarea>
            </div>
            <label class="d-flex align-center gap-sm" style="cursor:pointer;">
                <input type="checkbox" name="acak_soal" value="1">
                <span class="body-sm">Acak urutan soal untuk setiap mahasiswa</span>
            </label>
        </div>
    </div>

    {{-- Soal Builder --}}
    <div class="d-flex justify-between align-center mb-md">
        <h2 class="headline-md">Daftar Soal <span id="soal-count" class="badge badge-primary">0</span></h2>
        <div class="d-flex gap-sm">
            <button type="button" class="btn btn-primary btn-sm" onclick="addSoal('pilihan_ganda')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                + Pilihan Ganda
            </button>
            <button type="button" class="btn btn-outline btn-sm" onclick="addSoal('essay')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                + Essay
            </button>
        </div>
    </div>

    <div id="soal-container"></div>

    <div id="empty-soal" class="empty-state">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
        <h3>Belum Ada Soal</h3>
        <p>Klik tombol di atas untuk menambahkan soal pilihan ganda atau essay.</p>
    </div>

    {{-- Submit --}}
    <div class="d-flex justify-between align-center mt-lg" id="submit-area" style="display:none !important;">
        <p class="text-muted body-sm">Total soal: <strong id="total-soal-info">0</strong> · PG: <strong id="total-pg">0</strong> · Essay: <strong id="total-essay">0</strong></p>
        <button type="submit" class="btn btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Simpan Kuis
        </button>
    </div>
</form>

@push('scripts')
<script>
let soalIndex = 0;
const MAX_SOAL = 100;
const LETTERS = ['A', 'B', 'C', 'D', 'E'];

function addSoal(tipe) {
    if (soalIndex >= MAX_SOAL) {
        alert('Maksimal 100 soal per kuis.');
        return;
    }

    const container = document.getElementById('soal-container');
    const num = soalIndex;
    const label = tipe === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay';
    const badgeClass = tipe === 'pilihan_ganda' ? 'badge-primary' : 'badge-warning';

    let opsiHTML = '';
    if (tipe === 'pilihan_ganda') {
        opsiHTML = `
            <div class="form-group">
                <label class="form-label">Jumlah Opsi <span class="required">✱</span></label>
                <select class="form-control" id="opsi-count-${num}" onchange="updateOpsi(${num}, this.value)" style="max-width:160px;">
                    <option value="2">2 Opsi (A-B)</option>
                    <option value="3">3 Opsi (A-C)</option>
                    <option value="4" selected>4 Opsi (A-D)</option>
                    <option value="5">5 Opsi (A-E)</option>
                </select>
            </div>
            <div class="form-group" id="opsi-fields-${num}">
                <label class="form-label">Opsi Jawaban</label>
                <div class="d-flex gap-xs align-center mb-xs opsi-row" data-opsi="A"><strong style="width:24px;">A.</strong><input type="text" class="form-control" name="soal[${num}][opsi_a]" placeholder="Opsi A" required></div>
                <div class="d-flex gap-xs align-center mb-xs opsi-row" data-opsi="B"><strong style="width:24px;">B.</strong><input type="text" class="form-control" name="soal[${num}][opsi_b]" placeholder="Opsi B" required></div>
                <div class="d-flex gap-xs align-center mb-xs opsi-row" data-opsi="C"><strong style="width:24px;">C.</strong><input type="text" class="form-control" name="soal[${num}][opsi_c]" placeholder="Opsi C" required></div>
                <div class="d-flex gap-xs align-center mb-xs opsi-row" data-opsi="D"><strong style="width:24px;">D.</strong><input type="text" class="form-control" name="soal[${num}][opsi_d]" placeholder="Opsi D" required></div>
                <div class="d-flex gap-xs align-center mb-xs opsi-row" data-opsi="E" style="display:none;"><strong style="width:24px;">E.</strong><input type="text" class="form-control" name="soal[${num}][opsi_e]" placeholder="Opsi E"></div>
            </div>
            <input type="hidden" name="soal[${num}][jumlah_opsi]" id="jumlah-opsi-${num}" value="4">
            <div class="form-group">
                <label class="form-label">Jawaban Benar <span class="required">✱</span></label>
                <select class="form-control" name="soal[${num}][jawaban_benar]" id="jawaban-select-${num}" required style="max-width:200px;">
                    <option value="">-- Pilih --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>`;
    } else {
        opsiHTML = `
            <div class="form-group">
                <label class="form-label">Kunci Jawaban (untuk referensi penilaian)</label>
                <textarea class="form-control" name="soal[${num}][jawaban_benar]" placeholder="Jawaban yang diharapkan..." style="min-height:60px;"></textarea>
            </div>`;
    }

    const html = `
    <div class="card mb-md soal-card" id="soal-${num}" style="cursor:default; border-left: 3px solid var(--${tipe === 'pilihan_ganda' ? 'primary' : 'tertiary'});">
        <div class="card-header d-flex justify-between align-center" style="padding: var(--space-sm) var(--space-md);">
            <div class="d-flex align-center gap-sm">
                <span class="badge ${badgeClass}">${label}</span>
                <strong class="body-sm soal-number">Soal #${soalIndex + 1}</strong>
            </div>
            <button type="button" class="btn btn-ghost btn-sm" onclick="removeSoal(${num})" style="color:var(--error);">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                Hapus
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="soal[${num}][tipe]" value="${tipe}">
            <div class="form-group">
                <label class="form-label">Pertanyaan <span class="required">✱</span></label>
                <textarea class="form-control" name="soal[${num}][pertanyaan]" placeholder="Tulis pertanyaan..." required style="min-height:70px;"></textarea>
            </div>
            ${opsiHTML}
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    soalIndex++;
    updateCounter();
    container.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function updateOpsi(num, count) {
    count = parseInt(count);
    const fieldsContainer = document.getElementById('opsi-fields-' + num);
    const rows = fieldsContainer.querySelectorAll('.opsi-row');
    const jawabanSelect = document.getElementById('jawaban-select-' + num);
    const jumlahOpsiInput = document.getElementById('jumlah-opsi-' + num);

    jumlahOpsiInput.value = count;

    // Show/hide opsi rows
    rows.forEach(row => {
        const opsiLetter = row.dataset.opsi;
        const idx = LETTERS.indexOf(opsiLetter);
        const input = row.querySelector('input[type="text"]');
        if (idx < count) {
            row.style.display = 'flex';
            if (idx < 2) input.required = true; // A & B always required
            else input.required = true;
        } else {
            row.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    });

    // Update jawaban_benar select options
    const currentVal = jawabanSelect.value;
    jawabanSelect.innerHTML = '<option value="">-- Pilih --</option>';
    for (let i = 0; i < count; i++) {
        const opt = document.createElement('option');
        opt.value = LETTERS[i];
        opt.textContent = LETTERS[i];
        jawabanSelect.appendChild(opt);
    }
    // Restore selection if still valid
    if (LETTERS.indexOf(currentVal) < count) {
        jawabanSelect.value = currentVal;
    }
}

function removeSoal(index) {
    const el = document.getElementById('soal-' + index);
    if (el) {
        el.style.transition = 'opacity 0.2s, transform 0.2s';
        el.style.opacity = '0';
        el.style.transform = 'translateX(20px)';
        setTimeout(() => { el.remove(); updateCounter(); }, 200);
    }
}

function updateCounter() {
    const cards = document.querySelectorAll('.soal-card');
    const count = cards.length;
    document.getElementById('soal-count').textContent = count;
    document.getElementById('total-soal-info').textContent = count;
    document.getElementById('empty-soal').style.display = count === 0 ? 'flex' : 'none';
    document.getElementById('submit-area').style.cssText = count > 0 ? 'display:flex' : 'display:none !important';

    let pgCount = 0, essayCount = 0;
    cards.forEach((card, i) => {
        card.querySelector('.soal-number').textContent = 'Soal #' + (i + 1);
        if (card.querySelector('input[value="pilihan_ganda"]')) pgCount++;
        else essayCount++;
    });
    document.getElementById('total-pg').textContent = pgCount;
    document.getElementById('total-essay').textContent = essayCount;
}
</script>
@endpush
@endsection
