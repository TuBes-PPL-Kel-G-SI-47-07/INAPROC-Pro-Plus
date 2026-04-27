<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SurveyReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcurementRequestController;
use App\Http\Controllers\Admin\TenderConfigController;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD: Mengarah ke PortfolioController@index agar variabel $portfolios terisi
Route::get('/dashboard', [PortfolioController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// GROUP: AUTH (Bisa diakses semua user yang login)
Route::middleware('auth')->group(function () {
    // Profil Standar
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Fitur PBI-03: Portfolio Upload
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    
    // Fitur PBI-04: Survey Report (Endpoint penyimpanan data)
    Route::post('/survey-report', [SurveyReportController::class, 'store'])->name('survey.store');

    // Fitur PBI-06: Procurement Request (Riwayat Pengadaan)
    Route::get('/procurement', [ProcurementRequestController::class, 'index'])->name('procurement.index');
});

// GROUP: PEMOHON (Pengajuan Pengadaan)
Route::middleware(['auth', 'role:pemohon'])->group(function () {
    Route::get('/procurement/create', [ProcurementRequestController::class, 'create'])->name('procurement.create');
    Route::post('/procurement', [ProcurementRequestController::class, 'store'])->name('procurement.store');
});

// GROUP: ADMIN (Manajemen User & Akses Sistem)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// GROUP: VENDOR (PBI-02: Profile Setup)
Route::middleware(['auth', 'role:vendor'])->group(function () {
    // Route ini memanggil ProfileController@setup yang baru kita buat
    Route::get('/vendor/profile-setup', [ProfileController::class, 'setup'])->name('vendor.setup');
});

// GROUP: AUDITOR (Monitoring Proyek)
Route::middleware(['auth', 'role:auditor'])->group(function () {
    // Papar senarai laporan yang perlu disahkan
    Route::get('/auditor/surveys', [SurveyReportController::class, 'index'])->name('auditor.surveys.index');
    // Proses pengesahan (Approve/Reject)
    Route::patch('/auditor/surveys/{survey}/verify', [SurveyReportController::class, 'verify'])->name('auditor.surveys.verify');
    Route::get('/auditor/portfolios', [PortfolioController::class, 'auditorIndex'])->name('auditor.portfolios.index');
    Route::patch('/procurement/{id}/verify', [ProcurementRequestController::class, 'verify'])->name('procurement.verify');
});
    Route::post('/tender-config', [TenderConfigController::class, 'store'])
        ->middleware(['auth', 'role:admin'])
        ->name('tender-config.store');

require __DIR__.'/auth.php';