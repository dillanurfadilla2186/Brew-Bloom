<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Reservation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@brewbloom.com'],
            [
                'name' => 'Admin Brew & Bloom',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Categories
        $categoriesData = [
            ['name' => 'Coffee', 'slug' => 'coffee', 'icon' => '☕'],
            ['name' => 'Non-Coffee', 'slug' => 'non-coffee', 'icon' => '🥛'],
            ['name' => 'Pastry & Bakery', 'slug' => 'pastry-bakery', 'icon' => '🥐'],
            ['name' => 'Signature Blend', 'slug' => 'signature-blend', 'icon' => '✨'],
            ['name' => 'Artisan Tea', 'slug' => 'artisan-tea', 'icon' => '🍃'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Create Menus
        $menus = [
            [
                'category_id' => $categories['coffee']->id,
                'name' => 'Iced Latte',
                'slug' => 'iced-latte',
                'description' => 'Espresso dengan susu murni segar dingin dan lapisan foam lembut.',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['pastry-bakery']->id,
                'name' => 'Butter Croissant',
                'slug' => 'butter-croissant',
                'description' => 'Croissant Prancis klasik dengan butter premium, renyah di luar lembut di dalam.',
                'price' => 22000,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab794f4afe5a?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['non-coffee']->id,
                'name' => 'Matcha Latte',
                'slug' => 'matcha-latte',
                'description' => 'Bubuk green tea Uji Kyoto autentik dipadukan dengan fresh milk.',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['signature-blend']->id,
                'name' => 'Rich Belgian Chocolate',
                'slug' => 'rich-belgian-chocolate',
                'description' => 'Cokelat Belgia pekat dengan tekstur velvety, creamy, dan sensasi manis seimbang.',
                'price' => 30000,
                'image' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['coffee']->id,
                'name' => 'Caramel Macchiato',
                'slug' => 'caramel-macchiato',
                'description' => 'Espresso bold dengan vanilla syrup, steam milk, dan sirup caramel lezat.',
                'price' => 34000,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['pastry-bakery']->id,
                'name' => 'Almond Pain au Chocolat',
                'slug' => 'almond-pain-au-chocolat',
                'description' => 'Pastry berlapis isi cokelat leleh dengan topping almond panggang gurih.',
                'price' => 26000,
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['coffee']->id,
                'name' => 'Classic Americano',
                'slug' => 'classic-americano',
                'description' => 'Double shot espresso murni dari biji arabica pilihan dengan cita rasa floral fruity.',
                'price' => 24000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['artisan-tea']->id,
                'name' => 'Berry Hibiscus Cold Brew Tea',
                'slug' => 'berry-hibiscus-tea',
                'description' => 'Teh seduh dingin bunga rosella dan aneka buah beri organik yang menyegarkan.',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['signature-blend']->id,
                'name' => 'Bloom Palm Sugar Latte',
                'slug' => 'bloom-palm-sugar-latte',
                'description' => 'Kopi susu gula aren signature racikan barista dengan hint aroma pandan.',
                'price' => 26000,
                'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['coffee']->id,
                'name' => 'Cappuccino Brulee',
                'slug' => 'cappuccino-brulee',
                'description' => 'Cappuccino hangat dengan lapisan gula bakar karamel di atas foam.',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=600',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['pastry-bakery']->id,
                'name' => 'New York Cheesecake',
                'slug' => 'ny-cheesecake',
                'description' => 'Kue keju lembut premium dengan crust biskuit renyah dan berry coulis.',
                'price' => 35000,
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=600',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $categories['artisan-tea']->id,
                'name' => 'Chamomile Honey Lavender',
                'slug' => 'chamomile-honey-lavender',
                'description' => 'Teh herbal relaksasi dengan bunga chamomile, madu hutan, dan lavender alami.',
                'price' => 27000,
                'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=600',
                'is_featured' => false,
                'is_available' => true,
            ],
        ];

        foreach ($menus as $m) {
            Menu::create($m);
        }

        // 4. Create Sample Reservations
        Reservation::create([
            'booking_code' => 'BNB-' . strtoupper(Str::random(6)),
            'customer_name' => 'Dimas Pratama',
            'customer_phone' => '081298765432',
            'customer_email' => 'dimas@example.com',
            'reservation_date' => now()->addDay()->format('Y-m-d'),
            'reservation_time' => '19:00',
            'guests' => 4,
            'seating_area' => 'Outdoor Garden',
            'notes' => 'Tolong sediakan meja dekat lampu estetik untuk perayaan ulang tahun.',
            'status' => 'confirmed',
        ]);

        Reservation::create([
            'booking_code' => 'BNB-' . strtoupper(Str::random(6)),
            'customer_name' => 'Anisa Rahma',
            'customer_phone' => '085712345678',
            'customer_email' => 'anisa@example.com',
            'reservation_date' => now()->addDays(2)->format('Y-m-d'),
            'reservation_time' => '14:30',
            'guests' => 2,
            'seating_area' => 'Indoor AC',
            'notes' => 'Dekat colokan listrik untuk meeting kerja.',
            'status' => 'pending',
        ]);
    }
}
