<?php

use App\Http\Controllers\Admin\ManagerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\RoomController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        return Inertia::render('AdminDashboard', [
            'admin' => true,
        ]);
    })->name('admin.dashboard');
});






Route::prefix('admin/managers')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ManagerController::class, 'index'])->name('managers.index');
    Route::post('store', [ManagerController::class, 'store'])->name('managers.store');
    Route::put('{manager}/update', [ManagerController::class, 'update'])->name('managers.update');
    Route::delete('{manager}/destroy', [ManagerController::class, 'destroy'])->name('managers.destroy');
});


Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('manager/dashboard', function () {
        return Inertia::render('ManagerDashboard', [
            'manager' => true,
        ]);
    })->name('manager.dashboard');
});

Route::middleware(['auth', 'verified', 'role:admin|manager'])->prefix('admin/floors')->name('floors.')->group(function () {
    Route::get('/', [FloorController::class, 'index'])->name('index');
    Route::post('/store', [FloorController::class, 'store'])->name('store');
    Route::put('/{floor}/update', [FloorController::class, 'update'])->name('update');
    Route::delete('/{floor}/destroy', [FloorController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'verified', 'role:admin|manager'])->prefix('admin/rooms')->name('rooms.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index');
    Route::post('/store', [RoomController::class, 'store'])->name('store');
    Route::put('/{room}/update', [RoomController::class, 'update'])->name('update');
    Route::delete('/{room}/destroy', [RoomController::class, 'destroy'])->name('destroy');
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

