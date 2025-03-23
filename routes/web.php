<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard'); // Corrected component name
})->name('dashboard');

// Route::get('admin/dashboard', function () {
//     return Inertia::render('AdminDashboard');
// })->name('admin.dashboard');

Route::get('admin/dashboard', function () {
    return Inertia::render('Hello');
});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
