<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SurveyReportController;
use App\Http\Controllers\ProcurementRequestController;
use App\Http\Controllers\Admin\TenderConfigController;
use App\Http\Controllers\BidController; 
use App\Http\Controllers\ProjectProgressController;
use App\Http\Controllers\BastSubmissionController;
use App\Http\Controllers\AuditorAnalyticsController;
use App\Models\Bid;
use App\Models\Portfolio;
use App\Models\ActivityLog;
use App\Models\Tender;
use App\Models\ProcurementRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    // Data untuk Vendor: Menampilkan hasil kerja mereka sendiri (PBI-03)
    $portfolios = Portfolio::query()->where('user_id', Auth::id())->get();

    // Ambil parameter filter tender_id
    $filterTenderId = $request->query('tender_id');

    // Data untuk Admin/Auditor: Menampilkan peringkat vendor berdasarkan skor DSS (PBI-12)
    $competitiveMatrix = Bid::query()->with('user.surveyReport')
        ->when($filterTenderId, function(\Illuminate\Database\Eloquent\Builder $query) use ($filterTenderId) {
            return $query->where('tender_id', $filterTenderId);
        })
        ->orderBy('final_score', 'desc')
        ->get();

    // Data Log Aktivitas Sistem (Untuk Auditor & Admin)
    $activityLogs = ActivityLog::query()->with('user')->latest()->take(5)->get();

    // Data semua tender aktif atau tertutup untuk dropdown filter
    $allTenders = Tender::query()->latest()->get();

    // Data pengadaan yang sudah diapprove tapi belum dibuatkan tender
    $approvedProcurements = ProcurementRequest::query()->doesntHave('tender')->where('status', 'approved')->get();

    return view('dashboard', compact('portfolios', 'competitiveMatrix', 'activityLogs', 'allTenders', 'filterTenderId', 'approvedProcurements'));
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

    // Fitur PBI-06: Procurement Request (Riwayat Pengadaan)
    Route::get('/procurement', [ProcurementRequestController::class, 'index'])->name('procurement.index');
    Route::get('/procurement/{id}/spk', [ProcurementRequestController::class, 'generateSPK'])->name('procurement.spk');
});

// GROUP: PEMOHON (Pengajuan Pengadaan)
Route::middleware(['auth', 'role:pemohon'])->group(function () {
    Route::get('/procurement/create', [ProcurementRequestController::class, 'create'])->name('procurement.create');
    Route::post('/procurement', [ProcurementRequestController::class, 'store'])->name('procurement.store');


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
    
    // PBI-10: Sealed Bidding Encryption (Submit Penawaran)
    Route::post('/bid', [BidController::class, 'store'])->name('bid.store');

    // Riwayat Penawaran Vendor
    Route::get('/vendor/bids', [BidController::class, 'vendorBids'])->name('vendor.bids');
});

// GROUP: AUDITOR (Integritas & Validasi)
Route::middleware(['auth', 'role:auditor'])->group(function () {
    // Validasi Laporan Survey
    Route::get('/auditor/surveys', [SurveyReportController::class, 'index'])->name('auditor.surveys.index');
    Route::patch('/auditor/surveys/{survey}/verify', [SurveyReportController::class, 'verify'])->name('auditor.surveys.verify');
    
    // Audit Portofolio Vendor
    Route::get('/auditor/portfolios', [PortfolioController::class, 'auditorIndex'])->name('auditor.portfolios.index');
    Route::patch('/procurement/{id}/verify', [ProcurementRequestController::class, 'verify'])->name('procurement.verify');

    // Menetapkan Pemenang Tender
    Route::post('/bid/{id}/winner', [BidController::class, 'setWinner'])->name('bid.setWinner');
    
    // Form Input Survey Auditor
    Route::get('/auditor/surveys/create/{vendor_id}', [SurveyReportController::class, 'create'])->name('auditor.surveys.create');
});

Route::middleware(['auth', 'role:auditor|admin'])->group(function () {
    Route::get('/auditor/analytics', [AuditorAnalyticsController::class, 'index'])->name('auditor.analytics');
});

// Project Progress Routes (PBI 15)
Route::middleware('auth')->group(function () {
    Route::get('/progress', [ProjectProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/{id}', [ProjectProgressController::class, 'show'])->name('progress.show');
});

Route::middleware(['auth', 'role:auditor'])->group(function () {
    Route::post('/progress/{id}/verify', [ProjectProgressController::class, 'verify'])->name('progress.verify');
    Route::post('/bast/{id}/verify', [BastSubmissionController::class, 'verify'])->name('bast.verify');
});

Route::middleware(['auth', 'role:pemohon'])->group(function () {
    Route::post('/bast/{id}/verify-pemohon', [BastSubmissionController::class, 'verifyPemohon'])->name('bast.verify_pemohon');
});

Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::post('/progress', [ProjectProgressController::class, 'store'])->name('progress.store');
    Route::post('/bast', [BastSubmissionController::class, 'store'])->name('bast.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/bast/{id}/download', [BastSubmissionController::class, 'download'])->name('bast.download');
});

require __DIR__.'/auth.php';