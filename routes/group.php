<?php

use App\Http\Controllers\Masterfile\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('group')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('group.index');
    Route::post('/store', [GroupController::class, 'store'])->name('group.store');
});
