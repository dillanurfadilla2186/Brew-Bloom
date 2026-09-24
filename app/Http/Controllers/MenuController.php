<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    /**
     * Display the dedicated menu catalog page.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount(['menus' => function ($query) {
            $query->where('is_available', true);
        }])->get();

        $selectedCategory = $request->query('category');
        $searchQuery = $request->query('q');
        $sortBy = $request->query('sort', 'default');

        $query = Menu::with('category')->where('is_available', true);

        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->latest();
                break;
        }

        $menus = $query->get();

        $cafeInfo = [
            'name' => 'Brew & Bloom',
            'phone_raw' => '6281234567890',
        ];

        return view('menu.index', compact('categories', 'menus', 'selectedCategory', 'searchQuery', 'sortBy', 'cafeInfo'));
    }
}
