<?php

use App\Http\Controllers\SystemAdministration\UserMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::redirect('user', '/user');

    Route::prefix('user')->group(function () {
        Route::get('/', [UserMaintenanceController::class, 'index'])->name('user.index');
        Route::get('/create', [UserMaintenanceController::class, 'create'])->name('user.create');
        Route::post('/', [UserMaintenanceController::class, 'store'])->name('user.store');
        Route::get('/{user}', [UserMaintenanceController::class, 'show'])->name('user.show');
        Route::put('/{user}', [UserMaintenanceController::class, 'update'])->name(name: 'user.update');
        Route::delete('/{user}', [UserMaintenanceController::class, 'destroy'])->name('user.destroy');
    });
});
