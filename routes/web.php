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


<<<<<<< HEAD
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', function () {
        return Inertia::render('AdminDashboard', [
=======




Route::prefix('admin/managers')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ManagerController::class, 'index'])->name('managers.index');
    Route::get('create', [ManagerController::class, 'create'])->name('managers.create');
    Route::post('store', [ManagerController::class, 'store'])->name('managers.store');
    Route::get('{manager}/edit', [ManagerController::class, 'edit'])->name('managers.edit');
    Route::put('{manager}/update', [ManagerController::class, 'update'])->name('managers.update');
    Route::delete('{manager}/destroy', [ManagerController::class, 'destroy'])->name('managers.destroy');
});


Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('manager/dashboard', function () {
        return Inertia::render('ManagerDashboard', [
>>>>>>> eb6e066b112f1d9f5b1d26efa15499058e5162b5
            'manager' => true,
        ]);
    })->name('manager.dashboard');
});

Route::middleware(['auth', 'role:receptionist'])->group(function () {
    Route::get('receptionist/dashboard', function () {
        return Inertia::render('ReceptionistDashboard', [
            'receptionist' => true,
        ]);
    })->name('receptionist.dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
