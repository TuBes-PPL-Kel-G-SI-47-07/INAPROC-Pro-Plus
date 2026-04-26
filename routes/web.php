<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SurveyReportController;
use App\Http\Controllers\ProcurementRequestController;
use App\Http\Controllers\Admin\TenderConfigController;
use App\Http\Controllers\BidController; 
use App\Models\Bid;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| INAPROC+ Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD UTAMA (Integrasi PBI-03 & PBI-12)
 * Menggabungkan data Portfolio (Vendor) dan Competitive Matrix (Admin)
 */
Route::get('/dashboard', function () {
    // Data untuk Vendor: Menampilkan hasil kerja mereka sendiri (PBI-03)
    $portfolios = Portfolio::where('user_id', auth()->id())->get();

    // Data untuk Admin: Menampilkan peringkat vendor berdasarkan skor DSS (PBI-12)
    $competitiveMatrix = Bid::with('user.surveyReport')
        ->orderBy('final_score', 'desc')
        ->get();

    return view('dashboard', compact('portfolios', 'competitiveMatrix'));
})->middleware(['auth', 'verified'])->name('dashboard');

// GROUP: AUTH (Fitur General & Operasional)
Route::middleware('auth')->group(function () {
    // Management Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // PBI-03: Portfolio Submission
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    
    // PBI-04: Survey Report Processing
    Route::post('/survey-report', [SurveyReportController::class, 'store'])->name('survey.store');

    // PBI-06 & 07: Procurement & Smart Budget Check
    Route::post('/procurement', [ProcurementRequestController::class, 'store'])->name('procurement.store');

    // PBI-10: Sealed Bidding Encryption (Submit Penawaran)
    Route::post('/bid', [BidController::class, 'store'])->name('bid.store');
});

// GROUP: ADMIN (Penerbitan & Evaluasi)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // User Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // PBI-08 & 09: Tender Configuration & TOR Repository
    Route::post('/tender-config', [TenderConfigController::class, 'store'])->name('tender-config.store');

    // PBI-11: Auto-Scoring Engine Calculation
    Route::post('/bid/{id}/calculate', [BidController::class, 'calculateScore'])->name('bid.calculate');
});

// GROUP: VENDOR (Onboarding & Setup)
Route::middleware(['auth', 'role:vendor'])->group(function () {
    // PBI-02: Initial Profile Setup
    Route::get('/vendor/profile-setup', [ProfileController::class, 'setup'])->name('vendor.setup');
});

// GROUP: AUDITOR (Integritas & Validasi)
Route::middleware(['auth', 'role:auditor'])->group(function () {
    // Validasi Laporan Survey
    Route::get('/auditor/surveys', [SurveyReportController::class, 'index'])->name('auditor.surveys.index');
    Route::patch('/auditor/surveys/{survey}/verify', [SurveyReportController::class, 'verify'])->name('auditor.surveys.verify');
    
    // Audit Portofolio Vendor
    Route::get('/auditor/portfolios', [PortfolioController::class, 'auditorIndex'])->name('auditor.portfolios.index');
});

require __DIR__.'/auth.php';