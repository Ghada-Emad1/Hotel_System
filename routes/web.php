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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('admin/managers', ManagerController::class)->middleware('auth');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
