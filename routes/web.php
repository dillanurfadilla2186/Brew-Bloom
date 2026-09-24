<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuManagementController as AdminMenuController;
use App\Http\Controllers\Admin\ReservationManagementController as AdminReservationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dedicated Menu Catalog Page
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Dedicated Reservation Page
Route::get('/reservasi', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/reservasi/{code}/pdf', [ReservationController::class, 'downloadPdf'])->name('reservation.pdf');
Route::post('/reservasi/{code}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Management Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Menu Management
    Route::post('menus/bulk-action', [AdminMenuController::class, 'bulkAction'])->name('menus.bulk');
    Route::resource('menus', AdminMenuController::class)->except(['show']);
    Route::patch('menus/{menu}/toggle', [AdminMenuController::class, 'toggleAvailability'])->name('menus.toggle');

    // Reservation Management
    Route::post('reservations/bulk-action', [AdminReservationController::class, 'bulkAction'])->name('reservations.bulk');
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('/reservations/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');
});
