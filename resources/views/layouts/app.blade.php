<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'SobatMedis — Platform Pembelajaran Medis Online')">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — SobatMedis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    {{-- Top Navigation --}}
    <header class="topnav" id="topnav">
        <div class="container d-flex justify-between align-center">
            <a href="{{ url('/') }}" class="topnav-brand">
                @include('components.logo', ['size' => 56])
                <span>SobatMedis</span>
            </a>
            <nav class="topnav-links">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ url('/bantuan') }}" class="{{ request()->is('bantuan') ? 'active' : '' }}">Pusat Bantuan</a>
            </nav>
            <div class="topnav-actions">
                @auth
                    @php $role = auth()->user()->role; @endphp
                    <a href="{{ url("/{$role}/dashboard") }}" class="btn btn-ghost btn-sm">{{ auth()->user()->nama }}</a>
                    <form method="POST" action="{{ url('/logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-primary btn-sm" id="btn-login">Login</a>
                @endauth
                {{-- Tombol hamburger: hanya muncul di mobile --}}
                <button class="topnav-hamburger" id="topnav-hamburger" aria-label="Buka menu" aria-expanded="false">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

    {{-- Mobile Nav Drawer (hanya tampil di mobile) --}}
    <nav class="mobile-nav-drawer" id="mobile-nav-drawer">
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ url('/bantuan') }}" class="{{ request()->is('bantuan') ? 'active' : '' }}">Pusat Bantuan</a>
        <div class="drawer-divider"></div>
        <div class="drawer-actions">
            @auth
                @php $role = auth()->user()->role; @endphp
                <a href="{{ url("/{$role}/dashboard") }}" class="btn btn-ghost btn-sm btn-block">{{ auth()->user()->nama }}</a>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm btn-block">Logout</button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary btn-sm btn-block">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-outline btn-sm btn-block">Daftar</a>
            @endauth
        </div>
    </nav>

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

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container text-center">
            <p>© {{ date('Y') }} SobatMedis. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
