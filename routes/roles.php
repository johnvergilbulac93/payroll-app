<?php


use App\Http\Controllers\SystemAdministration\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->controller(RoleController::class)
    ->prefix('roles')
    ->name('roles.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/{employee}',  'update')->name(name: 'update');
        Route::delete('/{employee}',  'destroy')->name('destroy');
    });
