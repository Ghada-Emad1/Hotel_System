<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        return Inertia::render('AdminDashboard', [
            'admin' => true,
        ]);
    })->name('admin.dashboard');
});




Route::prefix('admin/managers')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
       return Inertia::render('Managers/Index');
    });
    });
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
