<?php

use App\Http\Controllers\Masterfile\EmployeeController;
use App\Http\Controllers\Processing\ProcessAttendanceController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->controller(P::class)->group(function () {

    Route::redirect('attendance', '/attendance/upload');

    Route::get('attendance/upload', [ProcessAttendanceController::class, 'index'])->name('attendance.processing.index');
    Route::post('attendance/upload', [ProcessAttendanceController::class, 'upload'])->name('attendance.processing.upload');
    Route::get('attendance/interpret_attendance_logs', [ProcessAttendanceController::class, 'interpretAttendanceLogs'])->name('attendance.processing.interpret_attendance_logs');
});
