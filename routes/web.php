<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
    // return Inertia::render('Welcome', [
    //     'canRegister' => Features::enabled(Features::registration()),
    // ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/employee.php';
require __DIR__ . '/attendance-processing.php';
require __DIR__ . '/user.php';
require __DIR__ . '/loans.php';
require __DIR__ . '/helpers.php';
