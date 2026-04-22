<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum (bisa diakses semua yang login)
Route::get('/dashboard', [PortfolioController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
});

// Group khusus ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
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
