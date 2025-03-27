<?php

use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ReceptionistController;
use App\Http\Controllers\Admin\ClientController;

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\ClientPendingController;
use App\Http\Controllers\Admin\ClientApprovalController;
use App\Http\Controllers\Admin\MyApprovedClientController;

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
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index');
    Route::post('store', [ManagerController::class, 'store'])->name('manager.store');
    Route::put('{manager}/update', [ManagerController::class, 'update'])->name('manager.update');
    Route::delete('{manager}/destroy', [ManagerController::class, 'destroy'])->name('manager.destroy');
});




Route::prefix('admin/receptionists')->middleware(['auth', 'verified', 'role:admin|manager'])->group(function () {
    Route::get('/', [ReceptionistController::class, 'index'])->name('receptionist.index');
    Route::post('store', [ReceptionistController::class, 'store'])->name('receptionist.store');
    Route::put('{receptionist}/update', [ReceptionistController::class, 'update'])->name('receptionist.update');
    Route::delete('{receptionist}/destroy', [ReceptionistController::class, 'destroy'])->name('receptionist.destroy');
    Route::post('/receptionists/{receptionist}/ban', [ReceptionistController::class, 'ban'])->name('receptionist.ban');
});

Route::prefix('manager/receptionists')->middleware(['auth', 'verified', 'role:admin|manager'])->group(function () {
    Route::get('/', [ReceptionistController::class, 'index'])->name('receptionist.index');
    Route::post('store', [ReceptionistController::class, 'store'])->name('receptionist.store');
    Route::put('{receptionist}/update', [ReceptionistController::class, 'update'])->name('receptionist.update');
    Route::delete('{receptionist}/destroy', [ReceptionistController::class, 'destroy'])->name('receptionist.destroy');
    Route::post('/receptionists/{receptionist}/unban', [ReceptionistController::class, 'unban'])->name('receptionist.unban');
});
Route::prefix('admin/clients')->middleware(['auth', 'verified','role:admin'])->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('client.index');
    Route::get('create', [ClientController::class, 'create'])->name('client.create');
    Route::post('store', [ClientController::class, 'store'])->name('client.store');
    Route::get('{client}/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('{client}/update', [ClientController::class, 'update'])->name('client.update');
    Route::get('pending', [ClientController::class, 'pending'])->name('pending');
    Route::post('clients/{client}/approve', [ClientController::class, 'approve'])->name('approve');

    Route::delete('{client}/destroy', [ClientController::class, 'destroy'])->name('client.destroy');
});



Route::prefix('receptionist/clients')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::get('create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('{client}/update', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('{client}/destroy', [ClientController::class, 'destroy'])->name('clients.destroy');
});



Route::prefix('admin/floors')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [FloorController::class, 'index'])->name('floor.index');
    Route::get('create', [FloorController::class, 'create'])->name('floor.create');
    Route::post('store', [FloorController::class, 'store'])->name('floor.store');
    Route::get('{floor}/edit', [FloorController::class, 'edit'])->name('floor.edit');
    Route::put('{floor}/update', [FloorController::class, 'update'])->name('floor.update');
    Route::delete('{floor}/destroy', [FloorController::class, 'destroy'])->name('floor.destroy');
});

Route::prefix('manager/floors')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [FloorController::class, 'index'])->name('floor.index');
    Route::get('create', [FloorController::class, 'create'])->name('floor.create');
    Route::post('store', [FloorController::class, 'store'])->name('floor.store');
    Route::get('{floor}/edit', [FloorController::class, 'edit'])->name('floor.edit');
    Route::put('{floor}/update', [FloorController::class, 'update'])->name('floor.update');
    Route::delete('{floor}/destroy', [FloorController::class, 'destroy'])->name('floor.destroy');
});
Route::middleware(['auth', 'verified', 'role:admin|manager'])->prefix('admin/rooms')->name('room.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index');
    Route::post('/store', [RoomController::class, 'store'])->name('store');
    Route::put('/{room}/update', [RoomController::class, 'update'])->name('update');
    Route::delete('/{room}/destroy', [RoomController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth', 'verified', 'role:receptionist|manager|admin'])
    ->prefix('receptionist/clients')
    ->name('receptionist_client.') // Ensure consistent route naming
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('create', [ClientController::class, 'create'])->name('create');
        Route::post('/store', [ClientController::class, 'store'])->name('store');
        Route::get('pending', [ClientController::class, 'pending'])->name('pending');
    Route::post('clients/{client}/approve', [ClientController::class, 'approve'])->name('approve');

    Route::get('{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('{client}/update', [ClientController::class, 'update'])->name('update');
        Route::delete('{client}/destroy', [ClientController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth', 'verified', 'role:receptionist'])
    ->prefix('receptionist/approve_clients')
    ->name('receptionist.approve_clients.') // Unique route name prefix
    ->group(function () {
        Route::get('/', [ClientPendingController::class, 'index'])->name('index');
        Route::get('pending', [ClientPendingController::class, 'pending'])->name('pending');
        Route::post('clients/{client}/approve', [ClientPendingController::class, 'approve'])->name('approve');
    });

Route::middleware(['auth', 'verified', 'role:receptionist'])
    ->prefix('receptionist/My_approved_clients')
    ->name('receptionist.myapprove_clients.') // Unique route name prefix
    ->group(function () {
        Route::get('/', [MyApprovedClientController::class, 'index'])->name('index');
        Route::get('pending', [MyApprovedClientController::class, 'pending'])->name('pending');
        Route::post('clients/{client}/approve', [MyApprovedClientController::class, 'approve'])->name('approve');
    });

Route::middleware(['auth', 'verified', 'role:manager'])
    ->prefix('manager/clients')
    ->name('client.') // Ensure consistent route naming
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('create', [ClientController::class, 'create'])->name('create');
        Route::post('/store', [ClientController::class, 'store'])->name('store');
        Route::get('pending', [ClientController::class, 'pending'])->name('pending');
        Route::post('clients/{client}/approve', [ClientController::class, 'approve'])->name('approve');

        Route::get('{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('{client}/update', [ClientController::class, 'update'])->name('update');
        Route::delete('{client}/destroy', [ClientController::class, 'destroy'])->name('destroy');
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



Route::middleware(['auth', 'verified', 'role:admin|manager'])->prefix('manager/rooms')->name('rooms.')->group(function () {
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

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('client/dashboard', function () {
        return Inertia::render('ClientDashboard', [
            'client' => true,
        ]);
    })->name('client.dashboard');
});



Route::middleware(['auth', 'verified', 'role:client'])->prefix('client/my_reservations')->name('reservation.')->group(function () {
    Route::get('/', [ReservationController::class, 'myReservations'])->name('index');
});

// Route::middleware(['auth', 'verified', 'role:client'])->prefix('client/make_reservations')->name('reservation.')->group(function () {
//     Route::get('/reservations/rooms/{room}', [ReservationController::class, 'bookRoom'])->name('reservations.book');
// });



Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::post('/create-checkout-session', [ReservationController::class, 'createCheckoutSession']);
    Route::get('/payment-success', [ReservationController::class, 'paymentSuccess']);
    Route::get('/payment-cancelled', [ReservationController::class, 'paymentCancelled']);

    Route::get('/reservations', [ReservationController::class, 'myReservations'])->name('reservations');
    Route::get('/reservations/make', [ReservationController::class, 'availableRooms'])->name('reservations.available');
    Route::get('/reservations/rooms/{room}', [ReservationController::class, 'bookRoom'])->name('reservations.book');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
