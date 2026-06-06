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

{{-- Pengajar Utama --}}
<section class="section">
    <div class="container">
        <div class="section-header animate-on-scroll" style="opacity: 0;">
            <h2>Pengajar Unggulan</h2>
            <p>Belajar langsung dari para ahli di bidang kedokteran dan kesehatan yang berpengalaman.</p>
        </div>

        <div class="grid grid-3 animate-on-scroll" style="opacity: 0;">
            @forelse($featuredPengajar ?? [] as $pengajar)
                <div class="card card-pengajar">
                    <img 
                        src="{{ $pengajar->foto_profile ? asset('storage/' . $pengajar->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($pengajar->nama) . '&size=192&background=cce5ff&color=004b73' }}" 
                        alt="{{ $pengajar->nama }}" 
                        class="pengajar-avatar"
                    >
                    <h3 class="pengajar-name">{{ $pengajar->nama }}</h3>
                    <p class="pengajar-specialty">{{ $pengajar->pengajarDetail->spesialisasi ?? 'Pengajar Medis' }}</p>
                    <div class="pengajar-rating">
                        <svg width="16" height="16" fill="#894d00" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        {{ number_format($pengajar->rating ?? 4.8, 1) }}
                    </div>
                </div>
            @empty
                @for($i = 0; $i < 3; $i++)
                <div class="card card-pengajar">
                    <img 
                        src="https://ui-avatars.com/api/?name=Dr+{{ ['Sari+Kusuma', 'Andi+Pratama', 'Budi+Santoso'][$i] }}&size=192&background=cce5ff&color=004b73" 
                        alt="Pengajar" 
                        class="pengajar-avatar"
                    >
                    <h3 class="pengajar-name">{{ ['Dr. Sari Kusuma', 'Dr. Andi Pratama', 'Dr. Budi Santoso'][$i] }}</h3>
                    <p class="pengajar-specialty">{{ ['Anatomi & Fisiologi', 'Farmakologi Klinis', 'Bedah Umum'][$i] }}</p>
                    <div class="pengajar-rating">
                        <svg width="16" height="16" fill="#894d00" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        {{ [4.9, 4.8, 4.7][$i] }}
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- Carousel Pengajar Tambahan --}}
<section class="section" style="background: var(--surface-container-low);">
    <div class="container">
        <div class="section-header animate-on-scroll" style="opacity: 0;">
            <h2>Semua Pengajar</h2>
            <p>Temukan pengajar yang tepat untuk bidang yang ingin kamu pelajari.</p>
        </div>

        <div class="carousel-section animate-on-scroll" style="opacity: 0;">
            <button class="carousel-btn carousel-btn-prev" aria-label="Previous">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <div style="overflow: hidden;">
                <div class="carousel-track">
                    @forelse($allPengajar ?? [] as $pengajar)
                        <div class="card card-pengajar">
                            <img 
                                src="{{ $pengajar->foto_profile ? asset('storage/' . $pengajar->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($pengajar->nama) . '&size=192&background=cce5ff&color=004b73' }}" 
                                alt="{{ $pengajar->nama }}" 
                                class="pengajar-avatar"
                            >
                            <h3 class="pengajar-name">{{ $pengajar->nama }}</h3>
                            <p class="pengajar-specialty">{{ $pengajar->pengajarDetail->spesialisasi ?? 'Pengajar Medis' }}</p>
                            <div class="pengajar-rating">
                                <svg width="16" height="16" fill="#894d00" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                {{ number_format($pengajar->rating ?? 4.5, 1) }}
                            </div>
                        </div>
                    @empty
                        @php
                            $dummyPengajar = [
                                ['Dr. Rina Wati', 'Patologi', 4.6],
                                ['Dr. Hasan Ali', 'Mikrobiologi', 4.5],
                                ['Dr. Maya Sari', 'Kardiologi', 4.9],
                                ['Dr. Rizky Pratama', 'Neurologi', 4.7],
                                ['Dr. Linda Kusuma', 'Pediatri', 4.8],
                                ['Dr. Fajar Nugroho', 'Dermatologi', 4.6],
                            ];
                        @endphp
                        @foreach($dummyPengajar as $dp)
                        <div class="card card-pengajar">
                            <img 
                                src="https://ui-avatars.com/api/?name={{ urlencode($dp[0]) }}&size=192&background=cce5ff&color=004b73" 
                                alt="{{ $dp[0] }}" 
                                class="pengajar-avatar"
                            >
                            <h3 class="pengajar-name">{{ $dp[0] }}</h3>
                            <p class="pengajar-specialty">{{ $dp[1] }}</p>
                            <div class="pengajar-rating">
                                <svg width="16" height="16" fill="#894d00" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                {{ $dp[2] }}
                            </div>
                        </div>
                        @endforeach
                    @endforelse
                </div>
            </div>

            <button class="carousel-btn carousel-btn-next" aria-label="Next">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
</section>

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
