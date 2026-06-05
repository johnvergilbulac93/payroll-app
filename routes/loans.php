<?php

use App\Http\Controllers\Masterfile\LoanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('loan.index');
    Route::get('/create', [LoanController::class, 'create'])->name('loan.create');
    Route::post('/store', [LoanController::class, 'store'])->name('loan.store');
    Route::get('/{loan}', [LoanController::class, 'show'])->name('loan.show');
    Route::put('/{loan}', [LoanController::class, 'update'])->name(name: 'loan.update');
    Route::delete('/{loan}', [LoanController::class, 'destroy'])->name('loan.destroy');
});
