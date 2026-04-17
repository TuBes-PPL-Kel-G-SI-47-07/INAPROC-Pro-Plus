<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum (bisa diakses semua yang login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group khusus ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
});

// Group khusus AUDITOR
Route::middleware(['auth', 'role:auditor'])->group(function () {
    Route::get('/auditor/monitoring', [MonitoringController::class, 'index'])->name('auditor.index');
});

// Group khusus VENDOR
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/profile-setup', [ProfileController::class, 'setup'])->name('vendor.setup');
});

require __DIR__.'/auth.php';
