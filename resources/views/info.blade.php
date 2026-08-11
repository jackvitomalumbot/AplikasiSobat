@extends('layouts.app')
@section('title', 'Info')
@section('meta_description', 'Informasi terbaru Sobat Medis — Prestasi, WHO News, Kelas Baru, dan statistik komunitas.')

@section('content')

{{-- ═══════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════ --}}
<section class="info-page-hero">
    <div class="info-container">
        <p class="info-eyebrow">Pusat Informasi</p>
        <h1 class="info-page-title">Info Sobat Medis</h1>
        <p class="info-page-subtitle">Prestasi terbaru, berita kesehatan dunia, kelas baru, dan perkembangan komunitas kami.</p>
    </div>
</section>

{{-- ═══════════════════════════════════════
     PRESTASI TERBARU
═══════════════════════════════════════ --}}
<section class="info-section" id="prestasi">
    <div class="info-container">
        <div class="info-section-header">
            <span class="info-section-num">01</span>
            <div>
                <h2 class="info-section-title">Prestasi Terbaru</h2>
                <p class="info-section-desc">Pencapaian membanggakan dari mahasiswa dan pengajar Sobat Medis.</p>
            </div>
        </div>

        @if($prestasiUtama->isNotEmpty())
        {{-- Featured --}}
        <div class="prestasi-featured-grid mb-xl">
            @foreach($prestasiUtama as $p)
            <div class="prestasi-featured-card">
                @if($p->foto)
                <div class="prestasi-featured-img-wrap">
                    <img src="{{ $p->foto_url }}" alt="{{ $p->judul }}" class="prestasi-featured-img" loading="lazy">
                </div>
                @endif
                <div class="prestasi-featured-body">
                    <span class="prestasi-badge prestasi-badge--featured">⭐ Prestasi Utama</span>
                    <h3 class="prestasi-featured-title">{{ $p->judul }}</h3>
                    @if($p->deskripsi)
                    <p class="prestasi-featured-desc">{{ $p->deskripsi }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="prestasi-sub-grid">
            {{-- Mahasiswa --}}
            @if($prestasiMahasiswa->isNotEmpty())
            <div class="prestasi-sub-col">
                <h3 class="prestasi-sub-title">Prestasi Mahasiswa</h3>
                <div class="prestasi-list">
                    @foreach($prestasiMahasiswa as $p)
                    <div class="prestasi-item">
                        @if($p->foto)
                        <img src="{{ $p->foto_url }}" alt="" class="prestasi-item-img" loading="lazy">
                        @else
                        <div class="prestasi-item-placeholder"></div>
                        @endif
                        <div class="prestasi-item-body">
                            <p class="prestasi-item-title">{{ $p->judul }}</p>
                            @if($p->deskripsi)
                            <p class="prestasi-item-desc">{{ Str::limit($p->deskripsi, 80) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Pengajar --}}
            @if($prestasiPengajar->isNotEmpty())
            <div class="prestasi-sub-col">
                <h3 class="prestasi-sub-title">Prestasi Pengajar</h3>
                <div class="prestasi-list">
                    @foreach($prestasiPengajar as $p)
                    <div class="prestasi-item">
                        @if($p->foto)
                        <img src="{{ $p->foto_url }}" alt="" class="prestasi-item-img" loading="lazy">
                        @else
                        <div class="prestasi-item-placeholder"></div>
                        @endif
                        <div class="prestasi-item-body">
                            <p class="prestasi-item-title">{{ $p->judul }}</p>
                            @if($p->deskripsi)
                            <p class="prestasi-item-desc">{{ Str::limit($p->deskripsi, 80) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        @if($prestasiUtama->isEmpty() && $prestasiMahasiswa->isEmpty() && $prestasiPengajar->isEmpty())
        <div class="info-empty">
            <span style="font-size:48px;"></span>
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
        <div class="info-section-header">
            <span class="info-section-num">02</span>
            <div>
                <h2 class="info-section-title">🌎 WHO Latest News</h2>
                <p class="info-section-desc">Berita kesehatan terkini dari World Health Organization. Diperbarui setiap 24 jam.</p>
            </div>
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
        <div class="info-section-header">
            <span class="info-section-num">03</span>
            <div>
                <h2 class="info-section-title">Sobat Medis Update</h2>
                <p class="info-section-desc">Kelas-kelas terbaru yang baru dibuka oleh pengajar kami.</p>
            </div>
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
        <div class="info-section-header info-section-header--light">
            <span class="info-section-num info-section-num--light">04</span>
            <div>
                <h2 class="info-section-title info-section-title--light">❤️ Community Highlight</h2>
                <p class="info-section-desc info-section-desc--light">Bergabung dengan ribuan pelajar yang sudah bersama Sobat Medis.</p>
            </div>
        </div>

        <div class="community-stats">
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-number" data-target="{{ $totalMahasiswa }}">0</div>
                <div class="stat-label">Mahasiswa Terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-number" data-target="{{ $totalPengajar }}">0</div>
                <div class="stat-label">Pengajar Profesional</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"></div>
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
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-top:var(--space-xl);">
            <a href="{{ url('/register') }}" class="btn-hero-primary">
                <span>MULAI BELAJAR</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ url('/bantuan') }}" class="btn btn-outline btn-lg">Hubungi Kami</a>
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
    padding: 72px 0 48px;
    background: var(--surface, #faf7f2);
    border-bottom: 1px solid rgba(0,0,0,0.06);
    text-align: center;
}
.info-eyebrow {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--on-surface-variant, #666);
    margin: 0 0 12px;
}
.info-page-title {
    font-size: clamp(32px, 5vw, 56px);
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.1;
    color: var(--on-surface, #1c1c1e);
    margin: 0 0 16px;
}
.info-page-subtitle {
    font-size: 17px;
    color: var(--on-surface-variant, #555);
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.7;
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
   01 PRESTASI
══════════════════ */
.prestasi-featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    justify-content: center;
    justify-items: center;
}
.prestasi-featured-card {
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 16px;
    overflow: hidden;
    padding: 28px;
    align-items: center;
    text-align: center;
    width: 100%;
    max-width: 480px;
    transition: box-shadow 0.2s;
}
.prestasi-featured-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
.prestasi-featured-img-wrap { width: 100px; height: 100px; border-radius: 16px; overflow: hidden; }
.prestasi-featured-img { width: 100%; height: 100%; object-fit: cover; }
.prestasi-featured-body { width: 100%; }
.prestasi-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 3px 10px;
    border-radius: 100px;
    margin-bottom: 10px;
}
.prestasi-badge--featured { background: #fff3cd; color: #856404; }
.prestasi-featured-title { font-size: 18px; font-weight: 700; margin: 0 0 8px; color: var(--on-surface, #1c1c1e); line-height: 1.3; }
.prestasi-featured-desc { font-size: 14px; color: var(--on-surface-variant, #555); margin: 0; line-height: 1.6; }

.prestasi-sub-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
@media (max-width: 768px) { .prestasi-sub-grid { grid-template-columns: 1fr; } }
.prestasi-sub-title { font-size: 15px; font-weight: 700; margin: 0 0 16px; color: var(--on-surface, #1c1c1e); }
.prestasi-list { display: flex; flex-direction: column; gap: 12px; }
.prestasi-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 16px;
    background: var(--surface, #fff);
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 12px;
    transition: box-shadow 0.2s;
}
.prestasi-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
.prestasi-item-img { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
.prestasi-item-placeholder {
    width: 48px; height: 48px; border-radius: 8px;
    background: var(--surface-container, #f0ede6);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.prestasi-item-title { font-size: 14px; font-weight: 600; margin: 0 0 4px; color: var(--on-surface, #1c1c1e); }
.prestasi-item-desc { font-size: 12px; color: var(--on-surface-variant, #666); margin: 0; line-height: 1.5; }
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
    padding: 40px 24px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
}
.stat-icon { font-size: 36px; margin-bottom: 16px; }
.stat-number {
    font-size: clamp(40px, 6vw, 64px);
    font-weight: 800;
    line-height: 1;
    color: #fff;
    letter-spacing: -0.04em;
    margin-bottom: 8px;
}
.stat-label { font-size: 14px; color: rgba(255,255,255,0.6); font-weight: 500; }

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
