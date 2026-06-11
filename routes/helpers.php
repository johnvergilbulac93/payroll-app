<?php

use App\Http\Controllers\Helper\HelperController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->controller(HelperController::class)->prefix('helpers')->name('helpers.')->group(function () {

    Route::prefix('loan_types')->group(function () {
        Route::get('/',  'getLoanType')->name('loanType.index');
        Route::post('/store', 'newLoanType')->name('loanType.store');
    });
    Route::prefix('groups')->group(function () {
        Route::get('/',  'index')->name('group.index');
        Route::post('/store', 'newGroup')->name('group.store');
    });
    Route::prefix('employees')->group(function () {
        Route::get('/',  'getEmployees')->name('employees.index');
    });
});
