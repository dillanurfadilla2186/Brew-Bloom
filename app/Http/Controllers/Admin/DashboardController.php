<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Reservation;

class DashboardController extends Controller
{
    /**
     * Display the Admin Overview Dashboard.
     */
    public function index()
    {
        $totalMenus = Menu::count();
        $availableMenus = Menu::where('is_available', true)->count();
        $totalCategories = Category::count();

        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $todayReservations = Reservation::whereDate('reservation_date', now()->toDateString())->count();

        $recentReservations = Reservation::with('items')->latest()->take(6)->get();
        $popularMenus = Menu::with('category')->where('is_featured', true)->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMenus',
            'availableMenus',
            'totalCategories',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'todayReservations',
            'recentReservations',
            'popularMenus'
        ));
    }
}
