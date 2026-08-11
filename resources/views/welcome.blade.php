@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'SobatMedis — Platform Pembelajaran Medis Online Terpercaya. Belajar dari pengajar profesional di bidang kedokteran dan kesehatan.')

@section('content')
{{-- ═══════════════════════════════════════════════════════════
     HERO SECTION — 2-column layout with Three.js 3D logo
════════════════════════════════════════════════════════════ --}}
<section class="hero hero-2col" id="heroSection" aria-label="Hero SobatMedis">
    <div class="hero-inner">

        {{-- ── LEFT: Content ── --}}
        <div class="hero-content" id="heroContent">
            <p class="hero-eyebrow" id="heroEyebrow">Platform Pembelajaran Medis</p>
            <h1 class="hero-title" id="heroHeading">
                Terpercaya<br>
                <span class="hero-title-accent">untuk Dunia Medis</span>
            </h1>
            <p class="hero-subtitle" id="heroSubtitle">
                Hubungkan dirimu dengan pengajar profesional di bidang kedokteran.
                Belajar kapan saja, di mana saja, dengan materi yang terstruktur dan berkualitas.
            </p>
            <div class="hero-actions" id="heroActions">
                <a href="{{ url('/register') }}" class="btn-hero-primary" id="heroCta">
                    <span>MULAI BELAJAR</span>
                    <svg class="btn-hero-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- ── RIGHT: 3D Logo ── --}}
        <div class="hero-visual" id="heroVisual" aria-hidden="true">
            {{-- Soft glow behind logo --}}
            <div class="hero-glow"></div>

            {{-- Three.js canvas --}}
            <canvas
                id="sobatMedisLogoCanvas"
                data-logo-url="{{ asset('images/logo.png') }}"
                class="hero-canvas"
                aria-hidden="true">
            </canvas>

            {{-- Fallback: shown only if JS/Three.js fails --}}
            <noscript>
                <img src="{{ asset('images/logo.png') }}" alt="Logo SobatMedis" class="hero-logo-fallback">
            </noscript>
            <img src="{{ asset('images/logo.png') }}" alt="" class="hero-logo-fallback" id="heroLogoFallback" style="display:none;" aria-hidden="true">
        </div>

    </div>
</section>

<style>
/* ═══════════════════════════════════════════════
   HERO 2-COLUMN — Premium Medical Education
   Standalone styles (no Tailwind dependency)
═══════════════════════════════════════════════ */

.hero-2col {
    position: relative;
    min-height: 88vh;
    padding: 0;
    display: flex;
    align-items: center;
    background: var(--surface, #faf7f2);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.hero-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 80px 48px;
    gap: 48px;
}

/* ── LEFT: Content ── */
.hero-content {
    text-align: left;
    opacity: 0;
    transform: translateX(-32px);
    transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-content.is-visible {
    opacity: 1;
    transform: translateX(0);
}

.hero-eyebrow {
    display: inline-block;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--on-surface-variant, #6c6c70);
    margin: 0 0 16px;
    opacity: 0;
    transform: translateY(12px);
    transition: opacity 0.6s 0.1s ease, transform 0.6s 0.1s ease;
}

.hero-content.is-visible .hero-eyebrow {
    opacity: 1;
    transform: translateY(0);
}

.hero-title {
    font-family: var(--font-headline, 'Hanken Grotesk', sans-serif);
    font-size: clamp(36px, 4.5vw, 62px);
    font-weight: 700;
    line-height: 1.08;
    letter-spacing: -0.03em;
    color: var(--on-surface, #1c1c1e);
    margin: 0 0 24px;
    max-width: 580px;
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.7s 0.2s ease, transform 0.7s 0.2s ease;
}

.hero-content.is-visible .hero-title {
    opacity: 1;
    transform: translateY(0);
}

.hero-title-accent {
    color: var(--on-surface-variant, #555);
    font-weight: 500;
}

.hero-subtitle {
    font-size: clamp(16px, 1.5vw, 19px);
    line-height: 1.7;
    color: var(--on-surface-variant, #444);
    max-width: 540px;
    margin: 0 0 40px;
    opacity: 0;
    transform: translateY(12px);
    transition: opacity 0.7s 0.35s ease, transform 0.7s 0.35s ease;
}

.hero-content.is-visible .hero-subtitle {
    opacity: 1;
    transform: translateY(0);
}

.hero-actions {
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.6s 0.5s ease, transform 0.6s 0.5s ease;
}

.hero-content.is-visible .hero-actions {
    opacity: 1;
    transform: translateY(0);
}

/* ── CTA Button ── */
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--on-surface, #1c1c1e);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    text-decoration: none;
    padding: 16px 36px;
    border-radius: 6px;
    transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.25s ease,
                background 0.2s ease;
    will-change: transform;
}

.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.22);
}

.btn-hero-arrow {
    transition: transform 0.25s ease;
}

.btn-hero-primary:hover .btn-hero-arrow {
    transform: translateX(5px);
}

/* ── RIGHT: Visual ── */
.hero-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    aspect-ratio: 1 / 1;
    max-width: 520px;
    margin: 0 auto;
    opacity: 0;
    transform: scale(0.88) translateY(16px);
    transition: opacity 0.9s 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.9s 0.3s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-visual.is-visible {
    opacity: 1;
    transform: scale(1) translateY(0);
}

/* Soft radial glow behind medallion */
.hero-glow {
    position: absolute;
    inset: 10%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(230, 220, 200, 0.55) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
    animation: glowPulse 6s ease-in-out infinite;
}

@keyframes glowPulse {
    0%, 100% { opacity: 0.7; transform: scale(1); }
    50%       { opacity: 1.0; transform: scale(1.05); }
}

.hero-canvas {
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;
    z-index: 1;
    border-radius: 50%;
}

.hero-logo-fallback {
    width: 75%;
    height: 75%;
    object-fit: contain;
    border-radius: 50%;
    z-index: 1;
}

/* ── TABLET ── */
@media (max-width: 1024px) {
    .hero-inner {
        padding: 60px 32px;
        gap: 32px;
    }
}

/* ── MOBILE ── */
@media (max-width: 768px) {
    .hero-2col {
        min-height: unset;
    }

    .hero-inner {
        grid-template-columns: 1fr;
        padding: 48px 24px 40px;
        gap: 40px;
    }

    .hero-content {
        text-align: left;
        order: 1;
    }

    .hero-visual {
        order: 2;
        max-width: 320px;
        margin: 0 auto;
        aspect-ratio: 1 / 1;
    }

    .hero-title {
        font-size: 32px;
        max-width: 100%;
    }

    .hero-subtitle {
        font-size: 16px;
        max-width: 100%;
    }
}

@media (max-width: 400px) {
    .hero-inner { padding: 36px 20px 32px; }
    .hero-title  { font-size: 28px; }
}
</style>

{{-- Entry Animation + Fallback logic --}}
@push('scripts')
<script>
(function () {
    'use strict';

    // Trigger CSS entry animations
    function triggerHeroEntry() {
        const content = document.getElementById('heroContent');
        const visual  = document.getElementById('heroVisual');
        if (content) content.classList.add('is-visible');
        // Visual slightly delayed
        setTimeout(() => { if (visual) visual.classList.add('is-visible'); }, 200);
    }

    // Immediately trigger (page already loaded)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', triggerHeroEntry);
    } else {
        triggerHeroEntry();
    }

    // Fallback: if Three.js canvas stays blank after 3s, show img fallback
    setTimeout(() => {
        const canvas = document.getElementById('sobatMedisLogoCanvas');
        const fallback = document.getElementById('heroLogoFallback');
        if (!canvas || !fallback) return;
        const ctx = canvas.getContext('webgl') || canvas.getContext('webgl2');
        if (!ctx) {
            canvas.style.display = 'none';
            fallback.style.display = 'block';
        }
    }, 3000);
})();
</script>
@endpush

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



{{-- ═══════════════════════════════════════════════════════════
     REKAN PENGAJAR SECTION
════════════════════════════════════════════════════════════ --}}
<section class="section rp-section" id="rekanPengajar">
    <div class="container">
        <div class="text-center mb-xl animate-on-scroll" style="opacity:0;">
            <p class="section-eyebrow">Tim Pengajar</p>
            <h2 class="headline-lg">Rekan Pengajar</h2>
            <p class="body-lg text-muted" style="max-width:520px;margin:0 auto;">
                Didukung oleh para tenaga medis berpengalaman yang siap membimbing perjalanan belajarmu.
            </p>
        </div>

        <div class="rp-grid animate-on-scroll" style="opacity:0;" id="rpGrid">
            @forelse($rekanPengajar ?? [] as $rekan)
                <div class="rp-card">
                    <div class="rp-avatar-wrap">
                        <img src="{{ $rekan->foto_url }}"
                            alt="{{ $rekan->nama }}"
                            class="rp-avatar"
                            loading="lazy"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rekan->nama) }}&size=128&background=e9e2cc&color=635e4d'">
                    </div>
                    <div class="rp-info">
                        <p class="rp-name">{{ $rekan->nama }}</p>
                        <p class="rp-specialty">{{ $rekan->spesialisasi ?? 'Pengajar Medis' }}</p>
                    </div>
                </div>
            @empty
                @php
                    $dummyRekan = [
                        ['nama' => 'Dr. Andi Prasetyo', 'spesialisasi' => 'Neurologi', 'bg' => 'd5e8f5&color=1a3a6e'],
                        ['nama' => 'Dr. Sari Wulandari', 'spesialisasi' => 'Pediatri', 'bg' => 'f5e8d5&color=6e3a1a'],
                        ['nama' => 'Dr. Budi Santoso', 'spesialisasi' => 'Ortopedi', 'bg' => 'e8f5d5&color=3a6e1a'],
                        ['nama' => 'Dr. Maya Indira', 'spesialisasi' => 'Anestesiologi', 'bg' => 'f5d5e8&color=6e1a3a'],
                        ['nama' => 'Dr. Reza Firmansyah', 'spesialisasi' => 'Urologi', 'bg' => 'd5f5e8&color=1a6e3a'],
                        ['nama' => 'Dr. Lestari Putri', 'spesialisasi' => 'Onkologi', 'bg' => 'e8d5f5&color=3a1a6e'],
                    ];
                @endphp
                @foreach($dummyRekan as $dr)
                <div class="rp-card">
                    <div class="rp-avatar-wrap">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($dr['nama']) }}&size=128&background={{ $dr['bg'] }}"
                            alt="{{ $dr['nama'] }}" class="rp-avatar" loading="lazy">
                    </div>
                    <div class="rp-info">
                        <p class="rp-name">{{ $dr['nama'] }}</p>
                        <p class="rp-specialty">{{ $dr['spesialisasi'] }}</p>
                    </div>
                </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

<style>
/* ══════════════════════════════════════
   Rekan Pengajar Section
══════════════════════════════════════ */
.rp-section {
    background: linear-gradient(180deg, var(--surface-container-low, #f8f5ef) 0%, var(--background, #faf7f2) 100%);
    padding: var(--space-3xl, 72px) 0;
}

.rp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: var(--space-lg, 24px);
    justify-items: center;
}

@media (max-width: 600px) {
    .rp-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md, 16px);
    }
}

.rp-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-sm, 10px);
    padding: var(--space-md, 16px) var(--space-sm, 10px);
    background: var(--surface, #fff);
    border: 1px solid var(--outline-variant, rgba(0,0,0,0.08));
    border-radius: var(--radius-lg, 16px);
    width: 100%;
    max-width: 170px;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: default;
}

.rp-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

.rp-avatar-wrap {
    position: relative;
    width: 72px;
    height: 72px;
    flex-shrink: 0;
}

.rp-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--outline-variant, rgba(0,0,0,0.1));
    display: block;
}

.rp-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.rp-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--on-surface, #1c1c1e);
    margin: 0;
    line-height: 1.3;
}

.rp-specialty {
    font-size: 11px;
    color: var(--on-surface-variant, #6c6c70);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin: 0;
    line-height: 1.3;
}
</style>

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
