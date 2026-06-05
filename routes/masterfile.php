<?php

use App\Http\Controllers\Helper\HelperController;
use App\Http\Controllers\Masterfile\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->controller(HelperController::class)->prefix('masterfile')->group(function () {

    Route::prefix('loan_type')->group(function () {
        Route::get('/',  'getLoanType')->name('masterfile.loanType.index');
        Route::post('/store', 'newLoanType')->name('masterfile.loanType.store');
    });
    Route::prefix('group')->group(function () {
        Route::get('/',  'index')->name('masterfile.group.index');
        Route::post('/store', 'newGroup')->name('masterfile.group.store');
    });
    Route::prefix('employee')->group(function () {
        Route::get('/',  'getEmployees')->name('masterfile.employees.index');
    });
});
