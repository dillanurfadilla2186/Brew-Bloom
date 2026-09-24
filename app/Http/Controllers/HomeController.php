<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Display the cafe landing page.
     */
    public function index()
    {
        $cafeInfo = [
            'name' => 'Brew & Bloom',
            'tagline' => 'Coffee • Food • Good Vibes',
            'headline' => 'Good Coffee,<br>Better Days',
            'description' => 'Nikmati kopi pilihan, pastry fresh setiap hari, dan suasana hangat untuk bekerja, ngobrol maupun menikmati waktu sendiri.',
            'phone' => '0812-3456-7890',
            'phone_raw' => '6281234567890',
            'instagram' => '@brewbloom.cafe',
            'address' => 'Jl. Melati No.123, Cirebon',
            'hours_weekday' => 'Senin – Jumat : 08.00 – 22.00',
            'hours_weekend' => 'Sabtu – Minggu : 07.00 – 23.00',
            'maps_url' => 'https://maps.google.com/?q=Jl.+Melati+No.123%2C+Cirebon',
            'maps_embed' => 'https://maps.google.com/maps?q=Jl.+Melati+No.123%2C+Cirebon&t=&z=15&ie=UTF8&iwloc=&output=embed',
        ];

        $stats = [
            ['value' => '500+', 'label' => 'Pelanggan Setia'],
            ['value' => '100%', 'label' => 'Premium Beans'],
            ['value' => '25+', 'label' => 'Varian Menu'],
            ['value' => '4.9★', 'label' => 'Google Rating'],
        ];

        // Fetch featured menus from database
        $menuItems = Menu::with('category')
            ->where('is_available', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        // If no featured items, fallback to any available
        if ($menuItems->isEmpty()) {
            $menuItems = Menu::with('category')->where('is_available', true)->take(8)->get();
        }

        $gallery = [
            'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=900',
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600',
            'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=600',
            'https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=600',
            'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600',
            'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600',
        ];

        $features = [
            [
                'icon' => '☕',
                'title' => 'Premium Beans',
                'description' => 'Biji kopi pilihan kualitas tinggi dengan profile roasting dan rasa konsisten.',
            ],
            [
                'icon' => '📶',
                'title' => 'Fast WiFi',
                'description' => 'Koneksi internet stabil & colokan melimpah, nyaman untuk WFC dan meeting.',
            ],
            [
                'icon' => '🥐',
                'title' => 'Fresh Pastry',
                'description' => 'Artisan pastry dipanggang segar setiap pagi dengan bahan berkualitas terbaik.',
            ],
            [
                'icon' => '🌿',
                'title' => 'Cozy Ambience',
                'description' => 'Interior aesthetic bernuansa hangat, cocok untuk bersantai maupun produktif.',
            ],
        ];

        $testimonial = [
            'quote' => 'Tempatnya nyaman banget buat kerja, kopinya konsisten enak, dan suasananya bikin betah berlama-lama.',
            'author' => 'Sarah M.',
            'rating' => 5,
        ];

        return view('home', compact('cafeInfo', 'stats', 'menuItems', 'gallery', 'features', 'testimonial'));
    }
}
