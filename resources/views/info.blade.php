@extends('layouts.app')
@section('title', 'Info')
@section('meta_description', 'Informasi terbaru Sobat Medis — Prestasi, WHO News, Kelas Baru, dan statistik komunitas.')

@section('content')

{{-- ═══════════════════════════════════════
     PAGE HEADER — 2-column like homepage
═══════════════════════════════════════ --}}
<section class="info-page-hero" id="infoPageHero">
    <div class="info-hero-inner">

        {{-- LEFT: Text --}}
        <div class="info-hero-content" id="infoHeroContent">
            <p class="info-eyebrow">Pusat Informasi</p>
            <h1 class="info-page-title">Info Sobat Medis</h1>
            <p class="info-page-subtitle">Prestasi terbaru, berita kesehatan dunia, kelas baru, dan perkembangan komunitas kami.</p>
        </div>

        {{-- RIGHT: 3D Logo --}}
        <div class="info-hero-logo" aria-hidden="true">
            <div class="info-hero-glow"></div>
            <canvas id="infoLogoCanvasRight"
                data-logo-url="{{ asset('images/logo.png') }}"
                class="info-hero-canvas"></canvas>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════
     PRESTASI TERBARU
═══════════════════════════════════════ --}}
<section class="info-section" id="prestasi">
    <div class="info-container">
        <div class="info-section-header info-section-header--center">
            <h2 class="info-section-title">Prestasi Terbaru</h2>
            <p class="info-section-desc">Pencapaian membanggakan dari mahasiswa dan pengajar Sobat Medis.</p>
        </div>

        @if($prestasiUtama->isNotEmpty() || $prestasiMahasiswa->isNotEmpty() || $prestasiPengajar->isNotEmpty())

        <div class="news-layout">

            {{-- ── Featured / Hero Card ── --}}
            @if($prestasiUtama->isNotEmpty())
            <div class="news-hero-col">
                @foreach($prestasiUtama->take(1) as $p)
                <div class="news-card news-card--hero">
                    <div class="news-card-img-wrap">
                        @if($p->foto)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->judul }}" class="news-card-img" loading="lazy">
                        @else
                        <div class="news-card-img-placeholder"><svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.3"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg></div>
                        @endif
                        <span class="news-badge news-badge--featured">Prestasi Utama</span>
                    </div>
                    <div class="news-card-body">
                        <h3 class="news-card-title">{{ $p->judul }}</h3>
                        @if($p->deskripsi)
                        <p class="news-card-desc">{{ $p->deskripsi }}</p>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Extra featured (jika >1) sebagai small cards --}}
                @foreach($prestasiUtama->skip(1) as $p)
                <div class="news-card news-card--sm">
                    @if($p->foto)
                    <img src="{{ $p->foto_url }}" alt="" class="news-card-sm-img" loading="lazy">
                    @else
                    <div class="news-card-sm-img news-card-sm-placeholder"></div>
                    @endif
                    <div class="news-card-sm-body">
                        <span class="news-badge news-badge--featured">Prestasi Utama</span>
                        <p class="news-card-sm-title">{{ $p->judul }}</p>
                        @if($p->deskripsi)
                        <p class="news-card-sm-desc">{{ Str::limit($p->deskripsi, 80) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- ── Side List (Mahasiswa + Pengajar) ── --}}
            @if($prestasiMahasiswa->isNotEmpty() || $prestasiPengajar->isNotEmpty())
            <div class="news-side-col">

                @if($prestasiMahasiswa->isNotEmpty())
                <p class="news-side-label">Prestasi Mahasiswa</p>
                @foreach($prestasiMahasiswa as $p)
                <div class="news-card news-card--sm">
                    @if($p->foto)
                    <img src="{{ $p->foto_url }}" alt="" class="news-card-sm-img" loading="lazy">
                    @else
                    <div class="news-card-sm-img news-card-sm-placeholder"></div>
                    @endif
                    <div class="news-card-sm-body">
                        <span class="news-badge news-badge--mhs">Mahasiswa</span>
                        <p class="news-card-sm-title">{{ $p->judul }}</p>
                        @if($p->deskripsi)
                        <p class="news-card-sm-desc">{{ Str::limit($p->deskripsi, 80) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
                @endif

                @if($prestasiPengajar->isNotEmpty())
                <p class="news-side-label" style="margin-top:24px;">Prestasi Pengajar</p>
                @foreach($prestasiPengajar as $p)
                <div class="news-card news-card--sm">
                    @if($p->foto)
                    <img src="{{ $p->foto_url }}" alt="" class="news-card-sm-img" loading="lazy">
                    @else
                    <div class="news-card-sm-img news-card-sm-placeholder"></div>
                    @endif
                    <div class="news-card-sm-body">
                        <span class="news-badge news-badge--pgj">Pengajar</span>
                        <p class="news-card-sm-title">{{ $p->judul }}</p>
                        @if($p->deskripsi)
                        <p class="news-card-sm-desc">{{ Str::limit($p->deskripsi, 80) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
                @endif

            </div>
            @endif

        </div>{{-- .news-layout --}}

        @else
        <div class="info-empty">
            <p>Prestasi akan tampil di sini setelah admin menambahkannya.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     WHO LATEST NEWS
═══════════════════════════════════════ --}}
<section class="info-section info-section--alt" id="who-news">
    <div class="info-container">
        <div class="info-section-header info-section-header--center">
            <h2 class="info-section-title">WHO Latest News</h2>
            <p class="info-section-desc">Berita kesehatan terkini dari World Health Organization. Diperbarui setiap 24 jam.</p>
        </div>

        @if(!empty($whoNews))
        <div class="who-grid">
            @foreach($whoNews as $news)
            <a href="{{ $news['link'] }}" target="_blank" rel="noopener noreferrer" class="who-card">
                <div class="who-card-badge">WHO</div>
                <p class="who-card-date">{{ $news['pubDateFormatted'] }}</p>
                <h3 class="who-card-title">{{ $news['title'] }}</h3>
                <p class="who-card-desc">{{ Str::limit($news['description'], 120) }}</p>
                <span class="who-card-link">
                    Baca Selengkapnya
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </a>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:var(--space-xl);">
            <a href="https://www.who.int/news" target="_blank" rel="noopener noreferrer" class="btn btn-outline">
                Lihat Semua WHO News →
            </a>
        </div>
        @else
        <div class="info-empty">
            <span style="font-size:48px;">🌎</span>
            <p>Berita WHO tidak dapat dimuat saat ini. Silakan kunjungi <a href="https://www.who.int/news" target="_blank" rel="noopener" style="color:var(--primary);">who.int</a> langsung.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     SOBAT MEDIS UPDATE
═══════════════════════════════════════ --}}
<section class="info-section" id="update">
    <div class="info-container">
        <div class="info-section-header info-section-header--center">
            <h2 class="info-section-title">Sobat Medis Update</h2>
            <p class="info-section-desc">Kelas-kelas terbaru yang baru dibuka oleh pengajar kami.</p>
        </div>

        @if($kelasBarru->isNotEmpty())
        <div class="kelas-grid">
            @foreach($kelasBarru as $kelas)
            <div class="kelas-card">
                @if($kelas->thumbnail)
                <div class="kelas-card-thumb">
                    <img src="{{ asset($kelas->thumbnail) }}" alt="{{ $kelas->nama_kelas }}" loading="lazy">
                </div>
                @else
                <div class="kelas-card-thumb kelas-card-thumb--placeholder">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.4"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                @endif
                <div class="kelas-card-body">
                    <p class="kelas-card-pengajar">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ $kelas->pengajar?->nama ?? 'Sobat Medis' }}
                    </p>
                    <h3 class="kelas-card-title">{{ $kelas->nama_kelas }}</h3>
                    @if($kelas->deskripsi)
                    <p class="kelas-card-desc">{{ Str::limit($kelas->deskripsi, 80) }}</p>
                    @endif
                    <div class="kelas-card-footer">
                        <span class="kelas-card-harga">{{ $kelas->formatted_harga }}</span>
                        <a href="{{ url('/register') }}" class="kelas-card-btn">Daftar</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="info-empty">
            <span style="font-size:48px;"></span>
            <p>Belum ada kelas terbaru. Pantau terus halaman ini!</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     COMMUNITY HIGHLIGHT
═══════════════════════════════════════ --}}
<section class="info-section info-section--dark" id="komunitas">
    <div class="info-container">
        <div class="info-section-header info-section-header--center info-section-header--light">
            <h2 class="info-section-title info-section-title--light">Community Highlight</h2>
            <p class="info-section-desc info-section-desc--light">Bergabung dengan ribuan pelajar yang sudah bersama Sobat Medis.</p>
        </div>

        <div class="community-stats">
            <div class="stat-card">
                <div class="stat-number" data-target="{{ $totalMahasiswa }}">0</div>
                <div class="stat-label">Mahasiswa Terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-target="{{ $totalPengajar }}">0</div>
                <div class="stat-label">Pengajar Profesional</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-target="{{ $totalKelas }}">0</div>
                <div class="stat-label">Kelas Aktif</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     05 — CTA
═══════════════════════════════════════ --}}
<section class="info-section info-cta-section" id="cta">
    <div class="info-container" style="text-align:center;">
        <p class="info-eyebrow">Bergabung Sekarang</p>
        <h2 class="info-cta-title">Terus berkembang bersama<br>Sobat Medis</h2>
        <p class="info-cta-desc">Mulai perjalanan belajarmu hari ini bersama pengajar terbaik di bidang kedokteran.</p>
        <div style="display:flex;justify-content:center;margin-top:var(--space-xl);">
            <a href="{{ url('/register') }}" class="btn-hero-primary">
                <span>MULAI BELAJAR</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ══════ STYLES ══════ --}}
<style>
/* ── Container ── */
.info-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
}
@media (max-width: 600px) { .info-container { padding: 0 20px; } }

/* ── Page Hero ── */
.info-page-hero {
    padding: 0;
    background: var(--surface, #faf7f2);
    border-bottom: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
}

/* 2-column inner — teks kiri, logo kanan */
.info-hero-inner {
    display: grid;
    grid-template-columns: 1fr 360px;
    align-items: center;
    min-height: 380px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
    gap: 40px;
}

/* LEFT: text — rata kiri */
.info-hero-content {
    text-align: left;
    padding: 72px 0;
    opacity: 0;
    transform: translateX(-16px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.info-hero-content.is-visible {
    opacity: 1;
    transform: translateX(0);
}

/* RIGHT: 3D logo */
.info-hero-logo {
    position: relative;
    width: 320px;
    height: 320px;
    justify-self: end;
    opacity: 0;
    transition: opacity 1s 0.2s ease;
}
.info-hero-logo.is-visible { opacity: 1; }

.info-hero-glow {
    position: absolute;
    inset: 5%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(220,210,190,0.5) 0%, transparent 70%);
    pointer-events: none;
    animation: glowPulse 6s ease-in-out infinite;
}

.info-hero-canvas {
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;
    border-radius: 50%;
}

/* Eyebrow & title — rata kiri */
.info-eyebrow {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--on-surface-variant, #666);
    margin: 0 0 12px;
}
.info-page-title {
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.1;
    color: var(--on-surface, #1c1c1e);
    margin: 0 0 16px;
}
.info-page-subtitle {
    font-size: 16px;
    color: var(--on-surface-variant, #555);
    max-width: 460px;
    margin: 0;
    line-height: 1.7;
}

/* Tablet */
@media (max-width: 900px) {
    .info-hero-inner {
        grid-template-columns: 1fr 260px;
        padding: 0 24px;
        gap: 24px;
    }
    .info-hero-logo { width: 240px; height: 240px; }
}

/* Mobile — stack, hide logo */
@media (max-width: 600px) {
    .info-hero-inner {
        grid-template-columns: 1fr;
        min-height: unset;
        padding: 0 20px;
    }
    .info-hero-logo { display: none; }
    .info-hero-content { padding: 48px 0; text-align: center; }
    .info-page-subtitle { margin: 0 auto; }
}

/* ── Section base ── */
.info-section { padding: 80px 0; }
.info-section--alt { background: var(--surface-container-low, #f5f2ec); }
.info-section--dark {
    background: var(--on-surface, #1c1c1e);
    color: #fff;
}

/* ── Section header ── */
.info-section-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 48px;
}
/* Centered variant — used by all sections now */
.info-section-header--center {
    display: block;
    text-align: center;
    margin-bottom: 48px;
}
.info-section-header--center .info-section-title { margin-left: auto; margin-right: auto; }
.info-section-header--center .info-section-desc  { margin-left: auto; margin-right: auto; max-width: 560px; }
.info-section-num {
    flex-shrink: 0;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.1em;
    color: var(--on-surface-variant, #888);
    padding-top: 4px;
}
.info-section-num--light { color: rgba(255,255,255,0.5); }
.info-section-title { font-size: clamp(22px, 3vw, 30px); font-weight: 700; margin: 0 0 6px; color: var(--on-surface, #1c1c1e); }
.info-section-title--light { color: #fff; }
.info-section-desc { font-size: 15px; color: var(--on-surface-variant, #555); margin: 0; }
.info-section-desc--light { color: rgba(255,255,255,0.65); }

/* ── Empty state ── */
.info-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--on-surface-variant, #888);
    font-size: 15px;
}
.info-empty p { margin: 12px 0 0; }

/* ══════════════════
   01 PRESTASI — News Card Layout
══════════════════ */

/* Outer 2-col: hero kiri, list kanan */
.news-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: flex-start;
}
@media (max-width: 900px) { .news-layout { grid-template-columns: 1fr; } }

/* ── Hero (featured) card ── */
.news-card--hero {
    border-radius: 20px;
    overflow: hidden;
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.07);
    transition: box-shadow 0.25s;
    cursor: default;
}
.news-card--hero:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.1); }

.news-card-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    background: var(--surface-container, #f0ede6);
}
.news-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.news-card--hero:hover .news-card-img { transform: scale(1.03); }
.news-card-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Badge over image */
.news-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 4px 12px;
    border-radius: 100px;
    backdrop-filter: blur(6px);
}
.news-badge--featured { background: rgba(255,243,205,0.92); color: #7a5900; }
.news-badge--mhs      { background: rgba(209,236,241,0.92); color: #0c5460; }
.news-badge--pgj      { background: rgba(212,237,218,0.92); color: #155724; }

/* Hero body */
.news-card-body {
    padding: 24px;
}
.news-card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--on-surface, #1c1c1e);
    margin: 0 0 10px;
    line-height: 1.35;
}
.news-card-desc {
    font-size: 14px;
    color: var(--on-surface-variant, #555);
    margin: 0;
    line-height: 1.7;
}

/* ── Small news card (horizontal) ── */
.news-card--sm {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 14px;
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 14px;
    transition: box-shadow 0.2s, transform 0.2s;
    margin-bottom: 12px;
}
.news-card--sm:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    transform: translateX(2px);
}

/* Thumbnail dalam sm card — tidak abs-positioned */
.news-card-sm-img {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--surface-container, #f0ede6);
}
.news-card-sm-placeholder { background: var(--surface-container, #f0ede6); }

.news-card-sm-body { flex: 1; min-width: 0; }
/* Badge dalam sm card — posisi relatif bukan absolute */
.news-card--sm .news-badge {
    position: relative;
    top: auto;
    left: auto;
    display: inline-block;
    margin-bottom: 6px;
    backdrop-filter: none;
    font-size: 10px;
}
.news-card-sm-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--on-surface, #1c1c1e);
    margin: 0 0 4px;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.news-card-sm-desc {
    font-size: 12px;
    color: var(--on-surface-variant, #666);
    margin: 0;
    line-height: 1.5;
}

/* Section label di atas list */
.news-side-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--on-surface-variant, #888);
    margin: 0 0 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(0,0,0,0.07);
}

.mb-xl { margin-bottom: 32px; }

/* ══════════════════
   02 WHO NEWS
══════════════════ */
.who-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    justify-content: center;
    justify-items: center;
}
.who-card {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 24px;
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 16px;
    text-decoration: none;
    color: inherit;
    text-align: center;
    align-items: center;
    width: 100%;
    max-width: 400px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.who-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.1);
}
.who-card-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: #0065b3;
    background: #e7f2fb;
    padding: 3px 10px;
    border-radius: 100px;
    width: fit-content;
}
.who-card-date { font-size: 12px; color: var(--on-surface-variant, #888); margin: 0; }
.who-card-title { font-size: 16px; font-weight: 700; color: var(--on-surface, #1c1c1e); margin: 0; line-height: 1.4; }
.who-card-desc { font-size: 13px; color: var(--on-surface-variant, #555); margin: 0; line-height: 1.6; flex: 1; }
.who-card-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #0065b3;
    margin-top: auto;
}

/* ══════════════════
   03 KELAS UPDATE
══════════════════ */
.kelas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    justify-content: center;
    justify-items: center;
}
.kelas-card {
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 16px;
    overflow: hidden;
    width: 100%;
    max-width: 360px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.kelas-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.1); }
.kelas-card-thumb { aspect-ratio: 16/9; overflow: hidden; background: var(--surface-container, #f0ede6); }
.kelas-card-thumb img { width: 100%; height: 100%; object-fit: cover; }
.kelas-card-thumb--placeholder { display: flex; align-items: center; justify-content: center; }
.kelas-card-body { padding: 20px; }
.kelas-card-pengajar {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; color: var(--on-surface-variant, #888);
    margin: 0 0 6px;
}
.kelas-card-title { font-size: 16px; font-weight: 700; margin: 0 0 6px; color: var(--on-surface, #1c1c1e); line-height: 1.4; }
.kelas-card-desc { font-size: 13px; color: var(--on-surface-variant, #666); margin: 0 0 16px; line-height: 1.5; }
.kelas-card-footer { display: flex; align-items: center; justify-content: space-between; }
.kelas-card-harga { font-size: 16px; font-weight: 700; color: var(--on-surface, #1c1c1e); }
.kelas-card-btn {
    font-size: 13px; font-weight: 600; letter-spacing: 0.05em;
    color: #fff; background: var(--on-surface, #1c1c1e);
    padding: 8px 18px; border-radius: 6px; text-decoration: none;
    transition: opacity 0.2s;
}
.kelas-card-btn:hover { opacity: 0.8; }

/* ══════════════════
   04 COMMUNITY
══════════════════ */
.community-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media (max-width: 600px) { .community-stats { grid-template-columns: 1fr; } }
.stat-card {
    text-align: center;
    padding: 40px 16px;
}
.stat-number {
    font-size: clamp(48px, 7vw, 72px);
    font-weight: 800;
    line-height: 1;
    color: #fff;
    letter-spacing: -0.04em;
    margin-bottom: 10px;
}
.stat-label {
    font-size: 12px;
    color: rgba(255,255,255,0.5);
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

/* ══════════════════
   05 CTA
══════════════════ */
.info-cta-section { background: var(--surface-container-low, #f5f2ec); }
.info-cta-title {
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 700;
    letter-spacing: -0.03em;
    margin: 12px 0 16px;
    color: var(--on-surface, #1c1c1e);
    line-height: 1.15;
}
.info-cta-desc { font-size: 17px; color: var(--on-surface-variant, #555); max-width: 480px; margin: 0 auto; line-height: 1.7; }

@media (max-width: 768px) {
    .info-section { padding: 56px 0; }
    .info-page-hero { padding: 48px 0 32px; }
    .prestasi-featured-grid { grid-template-columns: 1fr; }
    .who-grid { grid-template-columns: 1fr; }
    .kelas-grid { grid-template-columns: 1fr; }
    .info-section-header { flex-direction: column; gap: 8px; }
}
</style>

@push('scripts')
<script>
/* ── Animated counter for community stats ── */
(function() {
    function animateCounter(el) {
        const target = parseInt(el.dataset.target, 10);
        if (!target) { el.textContent = '0'; return; }
        const duration = 1400;
        const start    = performance.now();
        function step(ts) {
            const progress = Math.min((ts - start) / duration, 1);
            const ease     = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(ease * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.stat-number[data-target]').forEach(el => observer.observe(el));
})();
</script>
@endpush

@endsection
