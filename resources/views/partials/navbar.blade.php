<nav id="navbar">
    <a href="{{ route('home') }}" class="logo">
        <h2>{{ $cafeInfo['name'] ?? 'Brew & Bloom' }}</h2>
        <span>{{ $cafeInfo['tagline'] ?? 'Coffee • Food • Good Vibes' }}</span>
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('home') }}#home">Home</a></li>
        <li><a href="{{ route('home') }}#about">About</a></li>
        <li><a href="{{ route('menu.index') }}">Menu</a></li>
        <li><a href="{{ route('home') }}#gallery">Gallery</a></li>
        <li><a href="{{ route('home') }}#contact">Contact</a></li>
    </ul>

    <div class="nav-right">
        <a href="{{ route('reservation.index') }}" class="reserve-btn">Reservasi Meja</a>
        <button class="menu-toggle" id="menuToggle" aria-label="Buka Menu">☰</button>
    </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <div class="logo">
            <h2 style="color: var(--dark);">Brew & Bloom</h2>
        </div>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Tutup Menu">&times;</button>
    </div>
    <ul>
        <li><a href="{{ route('home') }}#home" class="mobile-link">Home</a></li>
        <li><a href="{{ route('home') }}#about" class="mobile-link">About</a></li>
        <li><a href="{{ route('menu.index') }}" class="mobile-link">Katalog Menu</a></li>
        <li><a href="{{ route('home') }}#gallery" class="mobile-link">Gallery</a></li>
        <li><a href="{{ route('home') }}#contact" class="mobile-link">Contact</a></li>
        <li style="margin-top: 15px;">
            <a href="{{ route('reservation.index') }}" class="btn btn-primary mobile-link" style="width: 100%; text-align: center;">Reservasi Online</a>
        </li>
    </ul>
</div>
