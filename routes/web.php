<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\MCMCController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProgressController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationSelection'])->name('register');
Route::get('/register/publicuser', [AuthController::class, 'showPublicUserRegistration'])->name('register.publicuser');
Route::get('/register/agency', [AuthController::class, 'showAgencyRegistration'])->name('register.agency');
Route::get('/register/mcmc', [AuthController::class, 'showMCMCRegistration'])->name('register.mcmc');
//arinja
Route::get('/register1', [PublicUserController::class, 'PublicUserRegistration'])->name('registration');
Route::get('/register/publicuser', [AuthController::class, 'showPublicUserRegistration'])->name('register.publicuser');
Route::get('/register/agency', [AuthController::class, 'showAgencyRegistration'])->name('register.agency');
Route::get('/register/mcmc', [AuthController::class, 'showMCMCRegistration'])->name('register.mcmc');

Route::post('/register/publicuser', [AuthController::class, 'registerPublicUser'])->name('register.publicuser.submit');
Route::post('/register/agency', [AuthController::class, 'registerAgency'])->name('register.agency.submit');
Route::post('/register/mcmc', [AuthController::class, 'registerMCMC'])->name('register.mcmc.submit');

// Public User Dashboard
Route::middleware('auth:publicuser')->group(function () {
    Route::get('/publicuser/dashboard', [PublicUserController::class, 'dashboard'])->name('publicuser.dashboard');
});

// Agency Dashboard
Route::middleware('auth:agency')->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
});

// MCMC Dashboard and Management
Route::middleware('auth:mcmc')->group(function () {
    Route::get('/mcmc/dashboard', [MCMCController::class, 'dashboard'])->name('mcmc.dashboard');

    // Agency Management
    Route::get('/mcmc/agencies', [MCMCController::class, 'manageAgencies'])->name('mcmc.agencies.index');
    Route::get('/mcmc/agencies/create', [MCMCController::class, 'createAgency'])->name('mcmc.agencies.create');
    Route::post('/mcmc/agencies', [MCMCController::class, 'storeAgency'])->name('mcmc.agencies.store');
    Route::get('/mcmc/agencies/{agency}/edit', [MCMCController::class, 'editAgency'])->name('mcmc.agencies.edit');
    Route::put('/mcmc/agencies/{agency}', [MCMCController::class, 'updateAgency'])->name('mcmc.agencies.update');
    Route::delete('/mcmc/agencies/{agency}', [MCMCController::class, 'destroyAgency'])->name('mcmc.agencies.destroy');
    Route::post('/mcmc/agencies/{agency}/reset-password', [MCMCController::class, 'resetAgencyPassword'])->name('mcmc.agencies.reset-password');

    // User Data
    Route::get('/mcmc/users', [MCMCController::class, 'viewAllUsers'])->name('mcmc.users.index');
    Route::get('/mcmc/users/{user}', [MCMCController::class, 'viewUserDetails'])->name('mcmc.users.show');

    // Reports
    Route::get('/mcmc/reports', [MCMCController::class, 'generateUserReport'])->name('mcmc.reports.index');
    Route::get('/mcmc/reports/download', [MCMCController::class, 'downloadUserReport'])->name('mcmc.reports.download');

    // Logs
    Route::get('/mcmc/activity-logs', [MCMCController::class, 'viewActivityLogs'])->name('mcmc.activity.index');

    // Inquiries (by InquiryController)
    Route::get('/mcmc/inquiries/new', [InquiryController::class, 'index'])->name('mcmc.inquiries.new');
    Route::get('/mcmc/inquiries/processed', [InquiryController::class, 'index'])->name('mcmc.inquiries.processed');
    Route::get('/mcmc/inquiries/{id}', [InquiryController::class, 'show'])->name('mcmc.inquiries.show');
    Route::post('/mcmc/inquiries/{id}/validate', [InquiryController::class, 'update'])->name('mcmc.inquiries.validate');

    Route::get('/mcmc/inquiry-reports', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.generate');
    Route::post('/mcmc/inquiry-reports/pdf', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.pdf');
    Route::post('/mcmc/inquiry-reports/excel', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.excel');

    Route::get('/mcmc/inquiry-activity', [InquiryController::class, 'history'])->name('mcmc.inquiry-activity.index');
});

// Inquiry Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/history', [InquiryController::class, 'history'])->name('inquiries.history');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
    Route::get('inquiries/{id}/delete', [InquiryController::class, 'delete'])->name('inquiries.delete');
    Route::resource('inquiries', InquiryController::class);
});

// Assignment Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('assignments', [ComplaintController::class, 'index'])->name('assignments.index');
    Route::get('assignments/report', [ComplaintController::class, 'generateAssignedReport'])->name('assignments.report');

    // MCMC only
    Route::get('inquiries/{inquiry}/assign', [ComplaintController::class, 'assignInquiry'])->name('assignments.assign');
    Route::post('inquiries/{inquiry}/assign', [ComplaintController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('assignments/{complaint}/reassign', [ComplaintController::class, 'reassignInquiry'])->name('assignments.reassign');
    Route::post('assignments/{complaint}/reassign', [ComplaintController::class, 'storeReassignment'])->name('assignments.storeReassignment');
    Route::get('assignments/rejected', [ComplaintController::class, 'rejectedAssignments'])->name('assignments.rejected');

    // Agency only
    Route::get('assignments/{complaint}/review', [ComplaintController::class, 'reviewInquiry'])->name('assignments.review');
    Route::post('assignments/{complaint}/review', [ComplaintController::class, 'updateReview'])->name('assignments.updateReview');
    Route::get('assignments/{complaint}/verify', [ComplaintController::class, 'verifyAssignment'])->name('assignments.verify');
    Route::post('assignments/{complaint}/verify', [ComplaintController::class, 'processVerification'])->name('assignments.processVerification');

    // Shared
    Route::get('assignments/{complaint}/view', [ComplaintController::class, 'viewAssignedInquiry'])->name('assignments.view');
    Route::get('assignments/{complaint}/history', [ComplaintController::class, 'trackAssignmentHistory'])->name('assignments.history');
});

// Inquiry Progress Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    // Public Users, Agency, MCMC can VIEW progress
    Route::get('/inquiry/{id}/progress/view', [\App\Http\Controllers\ProgressController::class, 'view'])->name('inquiry.progress.view');
});

// Only Agency and MCMC can EDIT or UPDATE progress
Route::middleware(['auth:agency,mcmc'])->group(function () {
    Route::get('/inquiry/{id}/progress/edit', [\App\Http\Controllers\ProgressController::class, 'edit'])->name('inquiry.progress.edit');
    Route::post('/inquiry/{id}/progress/update', [\App\Http\Controllers\ProgressController::class, 'update'])->name('inquiry.progress.update');
});

Route::middleware(['auth:publicuser,mcmc'])->group(function () {
    Route::get('/inquiries/search-by-status', [\App\Http\Controllers\ProgressController::class, 'searchInquiriesByStatus'])->name('inquiries.search.status');
});

Route::middleware(['auth:mcmc'])->group(function () {
    Route::get('/report/progress', [\App\Http\Controllers\ReportController::class, 'generateProgressReport'])->name('report.progress');
});

Route::get('/agency/feedback', [ProgressController::class, 'showFeedbackForm'])->name('feedback.form');
Route::post('/agency/feedback/submit', [ProgressController::class, 'submitFeedback'])->name('submit.feedback');
Route::get('/mcmc/alerts', [ProgressController::class, 'viewMcmcAlerts'])->name('mcmc.alerts');

// Jetstream auth dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Optional routes
Route::get('/home', [AuthController::class, 'home'])->name('home');

Route::middleware(['multiauth'])->get('/test-sidebar', function () {
    return view('test-sidebar');
})->name('test.sidebar');
