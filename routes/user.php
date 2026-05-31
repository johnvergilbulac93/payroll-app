<?php

use App\Http\Controllers\SystemAdministration\UserMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::redirect('user', '/user');

    Route::prefix('user')->group(function () {
        Route::get('/', [UserMaintenanceController::class, 'index'])->name('user.index');
        Route::get('/create', [UserMaintenanceController::class, 'create'])->name('user.create');

    });
});
