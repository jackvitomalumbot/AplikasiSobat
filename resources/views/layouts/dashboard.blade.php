<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SobatMedis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="has-sidebar">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-brand">
                @include('components.logo', ['size' => 48])
                <span>SobatMedis</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            @php $role = auth()->user()->role; @endphp

            @if($role === 'pengajar')
                <a href="{{ url('/pengajar/dashboard') }}" class="sidebar-link {{ request()->is('pengajar/dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ url('/pengajar/kelas') }}" class="sidebar-link {{ request()->is('pengajar/kelas*') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    Kelas Saya
                </a>
                <a href="{{ url('/pengajar/profile') }}" class="sidebar-link {{ request()->is('pengajar/profile') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                </a>
            @elseif($role === 'mahasiswa')
                <a href="{{ url('/mahasiswa/dashboard') }}" class="sidebar-link {{ request()->is('mahasiswa/dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ url('/mahasiswa/kelas') }}" class="sidebar-link {{ request()->is('mahasiswa/kelas*') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    Kelas Saya
                </a>
                <a href="{{ url('/mahasiswa/beli-kelas') }}" class="sidebar-link {{ request()->is('mahasiswa/beli-kelas*') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                    Beli Kelas
                </a>
                <a href="{{ url('/mahasiswa/transaksi') }}" class="sidebar-link {{ request()->is('mahasiswa/transaksi*') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    Riwayat Transaksi
                </a>
                <a href="{{ url('/mahasiswa/profile') }}" class="sidebar-link {{ request()->is('mahasiswa/profile') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                </a>
                <a href="{{ url('/mahasiswa/devices') }}" class="sidebar-link {{ request()->is('mahasiswa/devices') ? 'active' : '' }}">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Kelola Perangkat
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ auth()->user()->foto_profile ? asset(auth()->user()->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&size=40&background=cce5ff&color=004b73' }}" alt="" class="avatar">
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->nama }}</span>
                    <span class="sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm btn-block" style="justify-content: flex-start;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Mobile Header --}}
        <header class="mobile-header" id="mobile-header">
            <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <a href="{{ url('/') }}" class="sidebar-brand" style="font-size: 16px;">
                @include('components.logo', ['size' => 42])
                <span>SobatMedis</span>
            </a>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
