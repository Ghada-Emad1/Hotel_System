<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\ManagerController;



Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return Inertia::render('AdminDashboard', [
            'admin' => true,
        ]);
    })->name('admin.dashboard');


    Route::prefix('admin/managers')->name('managers.')->group(function () {
        Route::get('/', [ManagerController::class, 'index'])->name('index');
        Route::post('store', [ManagerController::class, 'store'])->name('store');
        Route::put('{manager}/update', [ManagerController::class, 'update'])->name('update');
        Route::delete('{manager}/destroy', [ManagerController::class, 'destroy'])->name('destroy');
    });
});


Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', function () {
        return Inertia::render('AdminDashboard', [
            'manager' => true,
        ]);
    })->name('manager.dashboard');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
