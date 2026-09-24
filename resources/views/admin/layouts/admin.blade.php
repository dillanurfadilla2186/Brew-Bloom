<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Brew & Bloom</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <span style="font-size: 24px;">☕</span>
                <div>
                    <h3>Brew & Bloom</h3>
                    <span>Management Panel</span>
                </div>
            </a>

            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span>📊</span> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <span>🍽️</span> Kelola Menu
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                        <span>📅</span> Data Reservasi
                    </a>
                </li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <a href="{{ route('home') }}" target="_blank">
                        <span>🌐</span> Lihat Website
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-admin btn-admin-danger" style="width: 100%; justify-content: center;">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <h2>@yield('header_title', 'Dashboard')</h2>
                <div class="admin-header-right">
                    <div class="user-badge">
                        <div class="user-avatar">A</div>
                        <div>
                            <div>{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <small style="color: var(--text-muted); font-size: 11px;">{{ Auth::user()->email ?? 'admin@brewbloom.com' }}</small>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅ {{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <span>❌ {{ session('error') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info">
                        <span>ℹ️ {{ session('info') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
