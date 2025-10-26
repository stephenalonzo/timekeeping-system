<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PunchController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return redirect(route('login'));
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// User Register & Login
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/authenticate', [AuthenticatedSessionController::class, 'store']);

// Timesheet Functions
Route::post('/punch', [PunchController::class, 'store']);
Route::get('/export', [PunchController::class, 'export']);
Route::get('/export/archive/{employeeId}/{month}', [PunchController::class, 'archiveExport'])->name('export.archive');

Route::get('/dashboard', [PunchController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PunchController::class, 'index'])->name('dashboard');
    Route::get('/employees', [PunchController::class, 'employees'])->name('employees');
    Route::get('/employees/{user:employeeId}', [PunchController::class, 'show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
