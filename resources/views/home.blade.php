@extends('layouts.app')

@section('title', ($cafeInfo['name'] ?? 'Brew & Bloom') . ' | Coffee • Food • Good Vibes')

@section('content')

    {{-- Hero Section --}}
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content reveal">
                <span class="hero-badge">WELCOME TO {{ strtoupper($cafeInfo['name'] ?? 'Brew & Bloom') }}</span>
                <h1>{!! $cafeInfo['headline'] ?? 'Good Coffee,<br>Better Days' !!}</h1>
                <p>{{ $cafeInfo['description'] ?? 'Nikmati kopi pilihan, pastry fresh setiap hari, dan suasana hangat untuk bekerja, ngobrol maupun menikmati waktu sendiri.' }}</p>
                <div class="hero-buttons">
                    <a href="#menu" class="btn btn-primary">Lihat Menu</a>
                    <a href="#contact" class="btn btn-outline">Reservasi Meja</a>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-img-wrapper reveal">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=900" alt="Suasana Cafe {{ $cafeInfo['name'] }}" loading="lazy">
                   
                </div>

                <div class="about-content reveal">
                    <span class="small-title">Tentang Kami</span>
                    <h2 class="section-title">Tempat dimana kopi bertemu kenyamanan.</h2>
                    <p class="description">
                        <strong>{{ $cafeInfo['name'] }}</strong> hadir untuk menjadi ruang nyaman di mana kamu bisa menikmati
                        kopi berkualitas terbaik, menu lezat, dan suasana yang hangat—baik untuk bekerja,
                        meeting produktif, maupun menikmati waktu santai bersama orang terdekat.
                    </p>

                    <div class="stats">
                        @foreach($stats as $stat)
                            <div class="stat-item">
                                <h3>{{ $stat['value'] }}</h3>
                                <p>{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <a href="#menu" class="btn btn-outline-dark">Jelajahi Menu Kami</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Menu Section --}}
    <section id="menu" class="menu-section">
        <div class="container reveal">
            <div class="menu-header">
                <div>
                    <span class="small-title">Menu Favorit</span>
                    <h2 class="section-title">Signature Menu & Treats</h2>
                </div>
                <a href="#contact" class="btn btn-primary">Pesan / Reservasi</a>
            </div>

            <div class="menu-grid">
                @foreach($menuItems as $item)
                    <div class="menu-card">
                        <div class="card-img-wrap">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" loading="lazy">
                            <span class="card-tag">{{ $item->category->name ?? 'Special' }}</span>
                        </div>
                        <div class="card-body">
                            <div>
                                <h3>{{ $item->name }}</h3>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div class="card-footer">
                                <span class="price">{{ $item->formatted_price ?? ('Rp' . number_format($item->price, 0, ',', '.')) }}</span>
                                <a href="https://wa.me/{{ $cafeInfo['phone_raw'] }}?text={{ urlencode('Halo ' . $cafeInfo['name'] . ', saya ingin memesan ' . $item->name . ' (' . ($item->formatted_price ?? $item->price) . ')') }}" target="_blank" rel="noopener noreferrer" class="order-btn">Pesan ➔</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Gallery Section --}}
    <section id="gallery" class="gallery">
        <div class="container reveal">
            <span class="small-title" style="color: var(--caramel-light);">Galeri Foto</span>
            <h2 class="section-title">Momen Hangat di {{ $cafeInfo['name'] }}</h2>

            <div class="gallery-grid">
                @foreach($gallery as $imgUrl)
                    <div class="gallery-item">
                        <img src="{{ $imgUrl }}" alt="Suasana Cafe" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features">
        <div class="container reveal">
            <span class="small-title">Keunggulan</span>
            <h2 class="section-title">Kenapa Memilih Kami?</h2>

            <div class="feature-grid">
                <!-- 01: Premium Beans -->
                <div class="feature-card">
                    <span class="feature-badge">01</span>
                    <div class="feature-anim-wrap">
                        <div class="anim-circle">
                            <svg class="anim-svg anim-coffee" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="42" fill="url(#coffeeGlow)" opacity="0.15" class="bg-pulse"/>
                                <!-- Steam Waves -->
                                <path class="steam steam-1" d="M42 36 C40 30, 44 24, 42 18" stroke="#C98A52" stroke-width="2.5" stroke-linecap="round"/>
                                <path class="steam steam-2" d="M50 33 C48 27, 52 21, 50 15" stroke="#C98A52" stroke-width="2.5" stroke-linecap="round"/>
                                <path class="steam steam-3" d="M58 36 C56 30, 60 24, 58 18" stroke="#C98A52" stroke-width="2.5" stroke-linecap="round"/>
                                <!-- Coffee Cup Body -->
                                <path d="M30 46 C30 64, 40 72, 50 72 C60 72, 70 64, 70 46 Z" fill="url(#cupGrad)" stroke="#6F4E37" stroke-width="2.5"/>
                                <!-- Cup Handle -->
                                <path d="M70 50 C77 50, 77 62, 70 62" stroke="#6F4E37" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                <!-- Saucer -->
                                <path d="M24 76 C36 80, 64 80, 76 76" stroke="#6F4E37" stroke-width="3" stroke-linecap="round"/>
                                <!-- Sparkles / Aroma -->
                                <circle cx="34" cy="28" r="2" fill="#C98A52" class="sparkle sp-1"/>
                                <circle cx="66" cy="24" r="2.2" fill="#C98A52" class="sparkle sp-2"/>
                                <defs>
                                    <radialGradient id="coffeeGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#C98A52"/>
                                        <stop offset="100%" stop-color="#6F4E37" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="cupGrad" x1="30" y1="46" x2="70" y2="72" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFFFFF"/>
                                        <stop offset="100%" stop-color="#EFE5DB"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <h3>Premium Beans</h3>
                    <p>Biji kopi pilihan kualitas tinggi dengan profile roasting dan rasa konsisten.</p>
                </div>

                <!-- 02: Fast WiFi -->
                <div class="feature-card">
                    <span class="feature-badge">02</span>
                    <div class="feature-anim-wrap">
                        <div class="anim-circle">
                            <svg class="anim-svg anim-wifi" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="42" fill="url(#wifiGlow)" opacity="0.15" class="bg-pulse"/>
                                <!-- WiFi Signal Arcs -->
                                <path class="wifi-wave wave-3" d="M24 38 A 38 38 0 0 1 76 38" stroke="#C98A52" stroke-width="3.2" stroke-linecap="round"/>
                                <path class="wifi-wave wave-2" d="M33 49 A 24 24 0 0 1 67 49" stroke="#C98A52" stroke-width="3.2" stroke-linecap="round"/>
                                <path class="wifi-wave wave-1" d="M42 60 A 11 11 0 0 1 58 60" stroke="#C98A52" stroke-width="3.2" stroke-linecap="round"/>
                                <!-- Central Beacon Dot -->
                                <circle class="wifi-dot" cx="50" cy="71" r="4.5" fill="#6F4E37"/>
                                <circle class="wifi-beacon" cx="50" cy="71" r="9" stroke="#C98A52" stroke-width="1.5" opacity="0.6"/>
                                <!-- Data Sparkles -->
                                <circle cx="28" cy="62" r="2" fill="#C98A52" class="sparkle sp-1"/>
                                <circle cx="72" cy="58" r="2" fill="#C98A52" class="sparkle sp-2"/>
                                <defs>
                                    <radialGradient id="wifiGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#C98A52"/>
                                        <stop offset="100%" stop-color="#6F4E37" stop-opacity="0"/>
                                    </radialGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <h3>Fast WiFi</h3>
                    <p>Koneksi internet stabil & colokan melimpah, nyaman untuk WFC dan meeting.</p>
                </div>

                <!-- 03: Fresh Pastry -->
                <div class="feature-card">
                    <span class="feature-badge">03</span>
                    <div class="feature-anim-wrap">
                        <div class="anim-circle">
                            <svg class="anim-svg anim-pastry" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="42" fill="url(#pastryGlow)" opacity="0.15" class="bg-pulse"/>
                                <!-- Baking Aroma Wisps -->
                                <path class="pastry-aroma ar-1" d="M36 32 C34 26, 38 22, 36 16" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <path class="pastry-aroma ar-2" d="M50 28 C48 22, 52 18, 50 12" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <path class="pastry-aroma ar-3" d="M64 32 C62 26, 66 22, 64 16" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <!-- Croissant / Pastry Shell -->
                                <path class="pastry-body" d="M22 66 C20 54, 35 42, 50 42 C65 42, 80 54, 78 66 C72 68, 62 60, 50 60 C38 60, 28 68, 22 66 Z" fill="url(#croissantGrad)" stroke="#6F4E37" stroke-width="2.5"/>
                                <!-- Crust Details -->
                                <path d="M38 46 C34 52, 34 60, 36 63" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <path d="M50 43 C48 50, 48 56, 50 60" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <path d="M62 46 C66 52, 66 60, 64 63" stroke="#C98A52" stroke-width="2" stroke-linecap="round"/>
                                <!-- Shimmer Stars -->
                                <path class="sparkle sp-1" d="M28 32 L30 28 L32 32 L36 34 L32 36 L30 40 L28 36 L24 34 Z" fill="#C98A52"/>
                                <path class="sparkle sp-2" d="M68 28 L69.5 25 L71 28 L74 29.5 L71 31 L69.5 34 L68 31 L65 29.5 Z" fill="#C98A52"/>
                                <defs>
                                    <radialGradient id="pastryGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#C98A52"/>
                                        <stop offset="100%" stop-color="#6F4E37" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="croissantGrad" x1="22" y1="42" x2="78" y2="68" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E8A663"/>
                                        <stop offset="60%" stop-color="#C98A52"/>
                                        <stop offset="100%" stop-color="#8B572A"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <h3>Fresh Pastry</h3>
                    <p>Artisan pastry dipanggang segar setiap pagi dengan bahan berkualitas terbaik.</p>
                </div>

                <!-- 04: Cozy Ambience -->
                <div class="feature-card">
                    <span class="feature-badge">04</span>
                    <div class="feature-anim-wrap">
                        <div class="anim-circle">
                            <svg class="anim-svg anim-cozy" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="42" fill="url(#cozyGlow)" opacity="0.15" class="bg-pulse"/>
                                <!-- Lamp Hanging Wire -->
                                <line x1="50" y1="14" x2="50" y2="34" stroke="#6F4E37" stroke-width="2"/>
                                <!-- Lamp Shade -->
                                <path d="M38 46 L62 46 L58 34 L42 34 Z" fill="#6F4E37" stroke="#6F4E37" stroke-width="2" stroke-linejoin="round"/>
                                <!-- Glowing Light Bulb / Flare -->
                                <circle class="lamp-bulb" cx="50" cy="49" r="4.5" fill="#FFF2C6"/>
                                <circle class="lamp-flare" cx="50" cy="51" r="14" fill="#FFDF7E" opacity="0.4"/>
                                <!-- Ambient Botanical Leaf -->
                                <g class="leaf-branch">
                                    <path d="M50 78 C48 68, 56 60, 68 58" stroke="#6F4E37" stroke-width="2.5" stroke-linecap="round"/>
                                    <path d="M68 58 C62 55, 60 48, 64 44 C68 48, 72 52, 68 58 Z" fill="#8FA876"/>
                                    <path d="M56 63 C52 60, 48 56, 52 50 C56 54, 58 58, 56 63 Z" fill="#A4BE8C"/>
                                    <path d="M62 70 C66 66, 72 65, 73 71 C68 73, 64 74, 62 70 Z" fill="#7D9965"/>
                                </g>
                                <!-- Ambient Warmth Sparkles -->
                                <circle cx="30" cy="58" r="2.2" fill="#C98A52" class="sparkle sp-1"/>
                                <circle cx="72" cy="38" r="1.8" fill="#C98A52" class="sparkle sp-2"/>
                                <defs>
                                    <radialGradient id="cozyGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#FFDF7E"/>
                                        <stop offset="100%" stop-color="#C98A52" stop-opacity="0"/>
                                    </radialGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <h3>Cozy Ambience</h3>
                    <p>Interior aesthetic bernuansa hangat, cocok untuk bersantai maupun produktif.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonial Section --}}
    <section class="testimonial">
        <div class="container reveal">
            <div class="testimonial-card">
                <div class="quote-icon">❝</div>
                <span class="small-title">Testimoni Pelanggan</span>
                <p>"{{ $testimonial['quote'] }}"</p>
                <div class="testimonial-author">— {{ $testimonial['author'] }}</div>
                <div class="stars">
                    @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                        ★
                    @endfor
                </div>
            </div>
        </div>
    </section>

    {{-- Contact & Location Section --}}
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info reveal">
                    <span class="small-title">Kunjungi Kami</span>
                    <h2 class="section-title">Lokasi & Jam Operasional</h2>
                    <p style="color: var(--text-muted);">Kami siap menyajikan kopi terbaik untuk menemani setiap aktivitasmu.</p>

                    <ul>
                        <li>
                            <div class="contact-icon-box">
                                <svg class="contact-svg c-phone" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g class="phone-waves">
                                        <path class="p-wave pw-1" d="M21 8 C23 10 23 14 21 16" stroke="#C98A52" stroke-width="1.5" stroke-linecap="round"/>
                                        <path class="p-wave pw-2" d="M24 5 C27 8 27 17 24 20" stroke="#C98A52" stroke-width="1.5" stroke-linecap="round"/>
                                    </g>
                                    <g class="phone-body">
                                        <path d="M10 6 C9 6 7.5 7.5 7.5 9 C7.5 15.5 13.5 21.5 20 21.5 C21.5 21.5 23 20 23 19 L20.5 15.5 L17.5 17 C15.5 15.5 13.5 13.5 12 11.5 L13.5 8.5 L10 6 Z" fill="url(#phoneGrad)" stroke="#6F4E37" stroke-width="1.5" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                        <linearGradient id="phoneGrad" x1="7.5" y1="6" x2="23" y2="21.5" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#E8A663"/>
                                            <stop offset="100%" stop-color="#C98A52"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <span>{{ $cafeInfo['phone'] }}</span>
                        </li>
                        <li>
                            <div class="contact-icon-box">
                                <svg class="contact-svg c-insta" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="cam-body" x="6" y="6" width="20" height="20" rx="6" stroke="#6F4E37" stroke-width="1.8" fill="url(#instaGrad)" fill-opacity="0.15"/>
                                    <circle class="cam-lens" cx="16" cy="16" r="5" stroke="#C98A52" stroke-width="1.8"/>
                                    <circle class="cam-lens-inner" cx="16" cy="16" r="2.2" fill="#6F4E37"/>
                                    <circle class="cam-flash" cx="21" cy="11" r="1.3" fill="#C98A52"/>
                                    <defs>
                                        <linearGradient id="instaGrad" x1="6" y1="6" x2="26" y2="26" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#E8A663"/>
                                            <stop offset="100%" stop-color="#C98A52"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <span>{{ $cafeInfo['instagram'] }}</span>
                        </li>
                        <li>
                            <div class="contact-icon-box">
                                <svg class="contact-svg c-clock" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="clock-face" cx="16" cy="16" r="10" stroke="#6F4E37" stroke-width="1.8" fill="url(#clockGrad)" fill-opacity="0.15"/>
                                    <circle cx="16" cy="16" r="1.5" fill="#6F4E37"/>
                                    <line class="clock-hour" x1="16" y1="16" x2="16" y2="10" stroke="#6F4E37" stroke-width="1.8" stroke-linecap="round"/>
                                    <line class="clock-min" x1="16" y1="16" x2="21" y2="16" stroke="#C98A52" stroke-width="1.5" stroke-linecap="round"/>
                                    <defs>
                                        <radialGradient id="clockGrad" cx="50%" cy="50%" r="50%">
                                            <stop offset="0%" stop-color="#FFDF7E"/>
                                            <stop offset="100%" stop-color="#C98A52"/>
                                        </radialGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div>
                                <div>{{ $cafeInfo['hours_weekday'] }}</div>
                                <div>{{ $cafeInfo['hours_weekend'] }}</div>
                            </div>
                        </li>
                    </ul>

                    <div style="margin-top: 25px;">
                        <a href="{{ $cafeInfo['maps_url'] ?? 'https://maps.google.com/?q=Jl.+Melati+No.123%2C+Cirebon' }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="gap: 8px;">
                            <span>🗺️</span> Petunjuk Arah di Google Maps
                        </a>
                    </div>
                </div>

                <div class="contact-img-wrap reveal">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=900" alt="Interior Cafe" loading="lazy">
                </div>
            </div>

            {{-- Interactive Google Maps Embed Below --}}
            <div class="contact-map-full reveal" style="margin-top: 45px;">
                <iframe 
                    src="{{ $cafeInfo['maps_embed'] ?? 'https://maps.google.com/maps?q=Jl.+Melati+No.123%2C+Cirebon&t=&z=15&ie=UTF8&iwloc=&output=embed' }}" 
                    width="100%" 
                    height="380" 
                    style="border:0; width: 100%; height: 380px; border-radius: 24px; box-shadow: var(--card-shadow); display: block;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

@endsection
