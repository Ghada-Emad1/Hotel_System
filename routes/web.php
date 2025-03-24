<?php

use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ReceptionistController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('create', [ManagerController::class, 'create'])->name('managers.create');
    Route::post('store', [ManagerController::class, 'store'])->name('managers.store');
    Route::get('{manager}/edit', [ManagerController::class, 'edit'])->name('managers.edit');
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

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('manager/dashboard', function () {
        return Inertia::render('AdminDashboard', [
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
