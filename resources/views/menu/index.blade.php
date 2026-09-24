@extends('layouts.app')

@section('title', 'Daftar Menu Pilihan | Brew & Bloom')

@push('styles')
<style>
    .menu-page-hero {
        padding: 140px 0 60px;
        background: linear-gradient(135deg, #231C18 0%, #3D291D 100%);
        color: #ffffff;
        text-align: center;
    }
    .menu-page-hero h1 {
        color: #ffffff;
        font-size: clamp(34px, 5vw, 52px);
        margin-bottom: 12px;
    }
    .menu-page-hero p {
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: auto;
    }
    .filter-bar {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px 25px;
        box-shadow: var(--card-shadow);
        margin-top: -35px;
        position: relative;
        z-index: 10;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }
    .category-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .pill {
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        color: var(--text);
        background: #F4EFEA;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pill:hover, .pill.active {
        background: var(--caramel);
        color: #ffffff;
    }
    .search-box {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .search-input {
        padding: 10px 18px;
        border-radius: 30px;
        border: 1px solid #E0D9D0;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        width: 220px;
        transition: var(--transition);
    }
    .search-input:focus {
        border-color: var(--caramel);
        box-shadow: 0 0 0 3px rgba(201, 138, 82, 0.2);
    }
    .search-btn {
        background: var(--caramel);
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 30px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }
    .menu-catalog {
        padding: 50px 0 100px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
    }
</style>
@endpush

@section('content')

    <section class="menu-page-hero">
        <div class="container">
            <span class="small-title" style="color: var(--caramel-light);">Crafted with Passion</span>
            <h1>Menu Pilihan Brew & Bloom</h1>
            <p>Dari biji kopi pilihan nusantara hingga pastry segar yang dipanggang setiap pagi.</p>
        </div>
    </section>

    <div class="container">
        <div class="filter-bar">
            <!-- Categories Filter -->
            <div class="category-pills">
                <a href="{{ route('menu.index') }}" class="pill {{ !$selectedCategory || $selectedCategory == 'all' ? 'active' : '' }}">
                    Semua ({{ $menus->count() }})
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->slug, 'q' => $searchQuery]) }}" 
                       class="pill {{ $selectedCategory == $category->slug ? 'active' : '' }}">
                        {{ $category->icon }} {{ $category->name }} ({{ $category->menus_count }})
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('menu.index') }}" method="GET" class="search-box">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari menu..." class="search-input">
                <button type="submit" class="search-btn">Cari</button>
                @if($searchQuery || $selectedCategory)
                    <a href="{{ route('menu.index') }}" class="pill" style="background:#eee; color:#666;">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <section class="menu-catalog">
        <div class="container">
            @if($menus->isEmpty())
                <div class="empty-state">
                    <span style="font-size: 48px;">☕</span>
                    <h3 style="margin-top: 15px;">Menu tidak ditemukan</h3>
                    <p style="color: var(--text-muted); margin-top: 5px;">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
                    <a href="{{ route('menu.index') }}" class="btn btn-primary" style="margin-top: 20px;">Lihat Semua Menu</a>
                </div>
            @else
                <div class="menu-grid">
                    @foreach($menus as $item)
                        <div class="menu-card reveal">
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
                                    <span class="price">{{ $item->formatted_price }}</span>
                                    <a href="https://wa.me/{{ $cafeInfo['phone_raw'] }}?text={{ urlencode('Halo ' . $cafeInfo['name'] . ', saya ingin memesan ' . $item->name . ' (' . $item->formatted_price . ')') }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="order-btn">
                                       Pesan WA ➔
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection
