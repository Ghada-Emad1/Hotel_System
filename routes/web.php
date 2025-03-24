<?php

use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ReceptionistController;
use App\Http\Controllers\ClientController;
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

Route::prefix('admin/receptionists')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ReceptionistController::class, 'index'])->name('receptionist.index');
    Route::get('create', [ReceptionistController::class, 'create'])->name('receptionist.create');
    Route::post('store', [ReceptionistController::class, 'store'])->name('receptionist.store');
    Route::get('{receptionist}/edit', [ReceptionistController::class, 'edit'])->name('receptionist.edit');
    Route::put('{receptionist}/update', [ReceptionistController::class, 'update'])->name('receptionist.update');
    Route::delete('{receptionist}/destroy', [ReceptionistController::class, 'destroy'])->name('receptionist.destroy');
});

Route::prefix('admin/clients')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('client.index');
    Route::get('create', [ClientController::class, 'create'])->name('client.create');
    Route::post('store', [ClientController::class, 'store'])->name('client.store');
    Route::get('{client}/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('{client}/update', [ClientController::class, 'update'])->name('client.update');
    Route::delete('{client}/destroy', [ClientController::class, 'destroy'])->name('client.destroy');
});

Route::prefix('admin/floors')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('floor.index');
    Route::get('create', [ClientController::class, 'create'])->name('floor.create');
    Route::post('store', [ClientController::class, 'store'])->name('floor.store');
    Route::get('{floor}/edit', [ClientController::class, 'edit'])->name('floor.edit');
    Route::put('{floor}/update', [ClientController::class, 'update'])->name('floor.update');
    Route::delete('{floor}/destroy', [ClientController::class, 'destroy'])->name('floor.destroy');
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('manager/dashboard', function () {
        return Inertia::render('ManagerDashboard', [
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

