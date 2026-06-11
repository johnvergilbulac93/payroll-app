<?php

use App\Http\Controllers\SystemAdministration\RoleController;
use App\Http\Controllers\SystemAdministration\UserMaintenanceController;
use App\Http\Controllers\SystemAdministration\UserPermissionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {

        Route::redirect('users', '/users/accounts');

        Route::controller(UserMaintenanceController::class)
            ->group(function () {
                Route::prefix('accounts')->name('accounts.')->group(function () {
                    Route::get('/', 'listUsers')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{user}',  'show')->name('show');
                    Route::put('/{user}', 'update')->name('update');
                    Route::delete('/{user}', 'destroy')->name('destroy');
                    Route::patch('/reset-password/{user}', 'resetPassword')->name('resetPassword');
                });

                Route::prefix('archives')->name('archives.')->group(function () {
                    Route::get('/', 'listArchives')->name('index');
                    Route::post('/restore/{user}', 'restore')->name('restore');
                    Route::delete('/{user}', 'deleteArchives')->name('destroy');
                });
            });

        Route::controller(RoleController::class)
            ->prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create',  'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{role}',  'show')->name('show');
                Route::put('/{role}',  'update')->name('update');
                Route::delete('/{role}',  'destroy')->name('destroy');
            });

        Route::controller(UserPermissionsController::class)
            ->prefix('permissions')
            ->name('permissions.')
            ->group(function () {

                Route::prefix('users')->name('users.')->group(function () {
                    Route::get('/{user}', 'index')->name('index');
                    Route::put('/{user}', 'userPermissionUpdate')->name('update');
                });
                Route::prefix('roles')->name('roles.')->group(function () {
                    Route::get('/{user}', 'index')->name('index');
                    Route::put('/{user}', 'rolePermissionUpdate')->name('update');
                });
            });
    });
