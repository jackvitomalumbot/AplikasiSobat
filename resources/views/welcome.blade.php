@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'SobatMedis — Platform Pembelajaran Medis Online Terpercaya. Belajar dari pengajar profesional di bidang kedokteran dan kesehatan.')

@section('content')
{{-- Hero Section --}}
<section class="hero">
    <div class="container">
        <h1 class="hero-title animate-slide-up">Platform Pembelajaran Medis Terpercaya</h1>
        <p class="hero-subtitle animate-slide-up">Hubungkan dirimu dengan pengajar profesional di bidang kedokteran. Belajar kapan saja, di mana saja, dengan materi yang terstruktur dan berkualitas.</p>
        <a href="{{ url('/register') }}" class="btn btn-primary btn-lg animate-slide-up">
            Mulai Belajar
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

{{-- Pengajar Unggulan — Premium Interactive Redesign --}}
<section class="section pu-section" id="pengajar-unggulan">
    <div class="container">
        <div class="section-header animate-on-scroll" style="opacity: 0;">
            <h2>Pengajar Unggulan</h2>
            <p>Belajar langsung dari para ahli di bidang kedokteran dan kesehatan yang berpengalaman.</p>
        </div>

        {{-- Motivational Quote --}}
        <div class="pu-motivasi animate-on-scroll" style="opacity: 0;" aria-live="polite">
            <span class="pu-motivasi-icon">✦</span>
            <span class="pu-motivasi-text" id="motivasiText">Ilmu kedokteran adalah cahaya yang menerangi jalan menuju kemanusiaan.</span>
            <span class="pu-motivasi-icon">✦</span>
        </div>

        {{-- Card Grid --}}
        <div class="pu-grid animate-on-scroll" style="opacity: 0;" id="puGrid">

            @forelse($featuredPengajar ?? [] as $pengajar)
                <div class="pu-card"
                    tabindex="0"
                    role="button"
                    aria-label="Lihat profil {{ $pengajar->nama }}"
                    data-nama="{{ $pengajar->nama }}"
                    data-spesialisasi="{{ $pengajar->spesialisasi ?? 'Pengajar Medis' }}"
                    data-foto="{{ $pengajar->foto_url }}"
                    data-keahlian="{{ $pengajar->keahlian ?? '' }}"
                    data-deskripsi="{{ $pengajar->deskripsi ?? '' }}"
                    data-motivasi="{{ $pengajar->motivasi ?? '' }}"
                    data-kelas-url="{{ url('/kelas') }}"
                    id="puCard{{ $loop->index }}">

                    <div class="pu-card-inner">
                        <div class="pu-avatar-wrap">
                            <img src="{{ $pengajar->foto_url }}"
                                alt="{{ $pengajar->nama }}"
                                class="pu-avatar" loading="lazy"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pengajar->nama) }}&size=256&background=e9e2cc&color=635e4d'">
                            <div class="pu-avatar-ring"></div>
                        </div>
                        <h3 class="pu-name">{{ $pengajar->nama }}</h3>
                        <p class="pu-specialty">{{ $pengajar->spesialisasi ?? 'Pengajar Medis' }}</p>
                        <div class="pu-card-hint">
                            <span>Lihat Profil</span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>

            @empty
                @php
                    $dummyPengajar = [
                        ['nama' => 'Dr. Nesya Cendranita', 'spesialisasi' => 'Obstetri & Ginekologi / Kandungan', 'deskripsi' => 'Dokter spesialis obstetri dan ginekologi dengan pengalaman luas dalam pelayanan kesehatan ibu, kehamilan, persalinan, dan kesehatan reproduksi wanita.', 'keahlian' => 'Kehamilan|Persalinan|Kesehatan Reproduksi|USG Obstetri', 'motivasi' => '"Setiap kehidupan baru yang lahir adalah bukti nyata dari ilmu dan kasih sayang."', 'bg' => 'dce8f0&color=1a4a6e'],
                        ['nama' => 'Dr. Elizabeth', 'spesialisasi' => 'Jantung', 'deskripsi' => 'Spesialis jantung berpengalaman yang berfokus pada pencegahan dan pengobatan penyakit kardiovaskular.', 'keahlian' => 'EKG|Ekokardiografi|Rehabilitasi Jantung|Hipertensi', 'motivasi' => '"Jantung yang sehat adalah fondasi kehidupan yang bermakna."', 'bg' => 'f0dce0&color=6e1a2a'],
                        ['nama' => 'Dr. Timotius Andrijun', 'spesialisasi' => 'Kulit & Kelamin', 'deskripsi' => 'Dokter spesialis kulit dan kelamin dengan keahlian dalam penanganan penyakit kulit infeksi, alergi, dan estetik.', 'keahlian' => 'Dermatitis|Akne|Infeksi Kulit|Kosmetik Medis', 'motivasi' => '"Kulit adalah cermin kesehatan, dan ilmu adalah kunci untuk membacanya."', 'bg' => 'e0f0dc&color=1a4a1e'],
                    ];
                @endphp
                @foreach($dummyPengajar as $i => $dp)
                <div class="pu-card"
                    tabindex="0"
                    role="button"
                    aria-label="Lihat profil {{ $dp['nama'] }}"
                    data-nama="{{ $dp['nama'] }}"
                    data-spesialisasi="{{ $dp['spesialisasi'] }}"
                    data-foto="https://ui-avatars.com/api/?name={{ urlencode($dp['nama']) }}&size=256&background={{ $dp['bg'] }}"
                    data-keahlian="{{ $dp['keahlian'] }}"
                    data-deskripsi="{{ $dp['deskripsi'] }}"
                    data-motivasi="{{ $dp['motivasi'] }}"
                    data-kelas-url="{{ url('/kelas') }}"
                    id="puCard{{ $i }}">

                    <div class="pu-card-inner">
                        <div class="pu-avatar-wrap">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($dp['nama']) }}&size=256&background={{ $dp['bg'] }}"
                                alt="{{ $dp['nama'] }}" class="pu-avatar" loading="lazy">
                            <div class="pu-avatar-ring"></div>
                        </div>
                        <h3 class="pu-name">{{ $dp['nama'] }}</h3>
                        <p class="pu-specialty">{{ $dp['spesialisasi'] }}</p>
                        <div class="pu-card-hint">
                            <span>Lihat Profil</span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforelse
        </div>

    </div>
</section>

{{-- Profile Overlay (Desktop) --}}
<div class="pu-overlay" id="puOverlay" role="dialog" aria-modal="true" aria-label="Profil Pengajar" hidden>
    <div class="pu-overlay-bg" id="puOverlayBg"></div>
    <div class="pu-profile-panel" id="puProfilePanel" tabindex="-1">
        <button class="pu-close-btn" id="puCloseBtn" aria-label="Tutup profil">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="pu-profile-top">
            <div class="pu-profile-avatar-wrap">
                <img src="" alt="" class="pu-profile-avatar" id="puProfileAvatar">
                <div class="pu-profile-avatar-ring"></div>
            </div>
            <div class="pu-profile-identity">
                <h2 class="pu-profile-name" id="puProfileName"></h2>
                <p class="pu-profile-spesialisasi" id="puProfileSpesialisasi"></p>

            </div>
        </div>

        <div class="pu-profile-motivasi" id="puProfileMotivasi"></div>

        <div class="pu-profile-body">
            <div class="pu-profile-section">
                <h4 class="pu-profile-section-title">Tentang Pengajar</h4>
                <p class="pu-profile-deskripsi" id="puProfileDeskripsi"></p>
            </div>

            <div class="pu-profile-section">
                <h4 class="pu-profile-section-title">Keahlian</h4>
                <div class="pu-profile-keahlian" id="puProfileKeahlian"></div>
            </div>


        </div>

        <div class="pu-profile-footer">
            <a href="#" class="btn btn-primary btn-lg" id="puLihatKelasBtn">
                Lihat Kelas
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Bottom Sheet (Mobile) --}}
<div class="pu-bs" id="puBottomSheet" role="dialog" aria-modal="true" aria-label="Profil Pengajar" hidden>
    <div class="pu-bs-bg" id="puBsBg"></div>
    <div class="pu-bs-sheet" id="puBsSheet">
        <div class="pu-bs-handle"></div>
        <button class="pu-bs-close" id="puBsCloseBtn" aria-label="Tutup profil">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="pu-bs-content">
            <div class="pu-bs-avatar-wrap">
                <img src="" alt="" class="pu-bs-avatar" id="puBsAvatar">
            </div>
            <h3 class="pu-bs-name" id="puBsName"></h3>
            <p class="pu-bs-spesialisasi" id="puBsSpesialisasi"></p>


            <div class="pu-bs-motivasi" id="puBsMotivasi"></div>

            <div class="pu-bs-section">
                <h4 class="pu-bs-section-title">Tentang Pengajar</h4>
                <p class="pu-bs-deskripsi" id="puBsDeskripsi"></p>
            </div>

            <div class="pu-bs-section">
                <h4 class="pu-bs-section-title">Keahlian</h4>
                <div class="pu-bs-keahlian" id="puBsKeahlian"></div>
            </div>



            <a href="#" class="btn btn-primary btn-block" id="puBsLihatKelas" style="margin-top: 8px;">
                Lihat Kelas
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    /* ── Motivasi Rotator ── */
    const motivasiList = [
        'Ilmu kedokteran adalah cahaya yang menerangi jalan menuju kemanusiaan.',
        'Dokter terbaik adalah yang tidak pernah berhenti belajar.',
        'Belajar dari yang terbaik adalah investasi terbesar dalam hidupmu.',
        'Setiap langkah dalam ilmu medis adalah langkah menuju kebaikan.',
        'Pengetahuan adalah obat paling mujarab yang bisa kamu berikan.',
        'Jadilah dokter yang tidak hanya menyembuhkan, tapi juga menginspirasi.',
    ];
    let motivasiIdx = 0;
    const motivasiEl = document.getElementById('motivasiText');
    if (motivasiEl) {
        setInterval(() => {
            motivasiEl.style.opacity = '0';
            motivasiEl.style.transform = 'translateY(-6px)';
            setTimeout(() => {
                motivasiIdx = (motivasiIdx + 1) % motivasiList.length;
                motivasiEl.textContent = motivasiList[motivasiIdx];
                motivasiEl.style.opacity = '1';
                motivasiEl.style.transform = 'translateY(0)';
            }, 350);
        }, 4500);
    }

    /* ── State ── */
    const isMobile = () => window.innerWidth < 768;
    let activeCard = null;
    let prevFocus = null;

    /* ── Elements ── */
    const overlay    = document.getElementById('puOverlay');
    const overlayBg  = document.getElementById('puOverlayBg');
    const panel      = document.getElementById('puProfilePanel');
    const closeBtn   = document.getElementById('puCloseBtn');
    const grid       = document.getElementById('puGrid');

    const bs         = document.getElementById('puBottomSheet');
    const bsBg       = document.getElementById('puBsBg');
    const bsSheet    = document.getElementById('puBsSheet');
    const bsCloseBtn = document.getElementById('puBsCloseBtn');

    /* ── Populate Profile Data ── */
    function populateDesktop(d) {
        document.getElementById('puProfileAvatar').src = d.foto;
        document.getElementById('puProfileAvatar').alt = d.nama;
        document.getElementById('puProfileName').textContent = d.nama;
        document.getElementById('puProfileSpesialisasi').textContent = d.spesialisasi;
        document.getElementById('puProfileMotivasi').textContent = d.motivasi;
        document.getElementById('puProfileDeskripsi').textContent = d.deskripsi;
        document.getElementById('puLihatKelasBtn').href = d.kelasUrl;

        const keahlianWrap = document.getElementById('puProfileKeahlian');
        keahlianWrap.innerHTML = '';
        (d.keahlian || '').split('|').forEach(k => {
            k = k.trim();
            if (!k) return;
            const tag = document.createElement('span');
            tag.className = 'pu-keahlian-tag';
            tag.textContent = k;
            keahlianWrap.appendChild(tag);
        });
    }

    function populateMobile(d) {
        document.getElementById('puBsAvatar').src = d.foto;
        document.getElementById('puBsAvatar').alt = d.nama;
        document.getElementById('puBsName').textContent = d.nama;
        document.getElementById('puBsSpesialisasi').textContent = d.spesialisasi;
        document.getElementById('puBsMotivasi').textContent = d.motivasi;
        document.getElementById('puBsDeskripsi').textContent = d.deskripsi;
        document.getElementById('puBsLihatKelas').href = d.kelasUrl;

        const keahlianWrap = document.getElementById('puBsKeahlian');
        keahlianWrap.innerHTML = '';
        (d.keahlian || '').split('|').forEach(k => {
            k = k.trim();
            if (!k) return;
            const tag = document.createElement('span');
            tag.className = 'pu-keahlian-tag';
            tag.textContent = k;
            keahlianWrap.appendChild(tag);
        });
    }

    /* ── Open Profile ── */
    function openProfile(card) {
        prevFocus = document.activeElement;
        activeCard = card;
        const d = {
            nama:       card.dataset.nama,
            spesialisasi: card.dataset.spesialisasi,
            rating:     card.dataset.rating,
            foto:       card.dataset.foto,
            keahlian:   card.dataset.keahlian,
            deskripsi:  card.dataset.deskripsi,
            motivasi:   card.dataset.motivasi,
            mahasiswa:  card.dataset.mahasiswa,
            kelas:      card.dataset.kelas,
            pengalaman: card.dataset.pengalaman,
            kelasUrl:   card.dataset.kelasUrl,
        };

        if (isMobile()) {
            populateMobile(d);
            openBottomSheet();
        } else {
            populateDesktop(d);
            openOverlay();
        }
    }

    /* ── Desktop Overlay ── */
    function openOverlay() {
        overlay.hidden = false;
        document.body.style.overflow = 'hidden';

        // Dim other cards
        document.querySelectorAll('.pu-card').forEach(c => {
            if (c !== activeCard) c.classList.add('pu-card--dimmed');
        });

        requestAnimationFrame(() => {
            overlay.classList.add('pu-overlay--visible');
            panel.classList.add('pu-panel--visible');
            setTimeout(() => { panel.focus(); }, 100);
        });
    }

    function closeOverlay() {
        overlay.classList.remove('pu-overlay--visible');
        panel.classList.remove('pu-panel--visible');

        document.querySelectorAll('.pu-card').forEach(c => c.classList.remove('pu-card--dimmed'));

        setTimeout(() => {
            overlay.hidden = true;
            document.body.style.overflow = '';
            if (prevFocus) prevFocus.focus();
        }, 420);
    }

    /* ── Mobile Bottom Sheet ── */
    function openBottomSheet() {
        bs.hidden = false;
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            bs.classList.add('pu-bs--visible');
            bsSheet.classList.add('pu-bs-sheet--visible');
            setTimeout(() => { bsCloseBtn.focus(); }, 100);
        });
    }

    function closeBottomSheet() {
        bsSheet.classList.remove('pu-bs-sheet--visible');
        bs.classList.remove('pu-bs--visible');

        setTimeout(() => {
            bs.hidden = true;
            document.body.style.overflow = '';
            if (prevFocus) prevFocus.focus();
        }, 420);
    }

    /* ── Events: Cards ── */
    document.querySelectorAll('.pu-card').forEach(card => {
        card.addEventListener('click', () => openProfile(card));
        card.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openProfile(card); }
        });
    });

    /* ── Events: Close Desktop ── */
    if (closeBtn) closeBtn.addEventListener('click', closeOverlay);
    if (overlayBg) overlayBg.addEventListener('click', closeOverlay);

    /* ── Events: Close Mobile ── */
    if (bsCloseBtn) bsCloseBtn.addEventListener('click', closeBottomSheet);
    if (bsBg) bsBg.addEventListener('click', closeBottomSheet);

    /* ── Swipe Down Mobile ── */
    let touchStartY = 0;
    if (bsSheet) {
        bsSheet.addEventListener('touchstart', e => { touchStartY = e.touches[0].clientY; }, { passive: true });
        bsSheet.addEventListener('touchend', e => {
            const delta = e.changedTouches[0].clientY - touchStartY;
            if (delta > 80) closeBottomSheet();
        }, { passive: true });
    }

    /* ── Keyboard: Escape ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (!overlay.hidden) closeOverlay();
            if (!bs.hidden) closeBottomSheet();
        }
    });

    /* ── Focus Trap Desktop ── */
    if (panel) {
        panel.addEventListener('keydown', e => {
            if (e.key !== 'Tab') return;
            const focusable = panel.querySelectorAll('button, a, [tabindex]:not([tabindex="-1"])');
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        });
    }
})();
</script>
@endpush



{{-- CTA Section --}}
<section class="section">
    <div class="container" style="text-align: center;">
        <div class="animate-on-scroll" style="opacity: 0;">
            <h2 class="headline-lg mb-md">Siap Untuk Memulai?</h2>
            <p class="body-lg text-muted mb-lg" style="max-width: 480px; margin-left: auto; margin-right: auto;">Bergabung dengan ribuan mahasiswa kedokteran yang sudah belajar bersama SobatMedis.</p>
            <div class="d-flex gap-md justify-center flex-wrap">
                <a href="{{ url('/register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
                <a href="{{ url('/bantuan') }}" class="btn btn-outline btn-lg">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>
@endsection
