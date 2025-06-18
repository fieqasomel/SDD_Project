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

// Test route for debugging
Route::get('/test-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Test route for auth debugging
Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'guards' => [
            'publicuser' => Auth::guard('publicuser')->check(),
            'agency' => Auth::guard('agency')->check(),
            'mcmc' => Auth::guard('mcmc')->check(),
        ],
        'user' => Auth::user() ? get_class(Auth::user()) : null
    ]);
});

// Simple login page for testing
Route::get('/login-simple', function () {
    return view('auth.login-simple');
});

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

<<<<<<< HEAD
// Home route for authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
});

// Protected Dashboard Routes
=======
// Public User Dashboard
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
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
<<<<<<< HEAD
=======

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
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
});

// Inquiry Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
<<<<<<< HEAD
    // Special routes that need to come before resource routes
    Route::get('inquiries/public', [InquiryController::class, 'publicInquiries'])->name('inquiries.public');
=======
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
<<<<<<< HEAD
    Route::get('inquiries/history', [InquiryController::class, 'inquiryHistory'])->name('inquiries.history');
    
    // Resource routes
    Route::resource('inquiries', InquiryController::class);
});

// Alternative individual middleware approach - if the above doesn't work
Route::group(['middleware' => ['auth:publicuser']], function () {
    Route::prefix('publicuser')->group(function () {
        Route::get('inquiries', [InquiryController::class, 'index'])->name('publicuser.inquiries');
        Route::get('inquiries/create', [InquiryController::class, 'create'])->name('publicuser.inquiries.create');
    });
});

// Assignment Management Routes - Available to all authenticated users
=======
    Route::get('inquiries/{id}/delete', [InquiryController::class, 'delete'])->name('inquiries.delete');
    Route::resource('inquiries', InquiryController::class);
});

// Assignment Routes
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
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
Route::get('/progress-view', [ProgressController::class, 'publicView'])->withoutMiddleware(['auth']);
Route::post('/update-inquiry-progress/{id}', [ProgressController::class, 'update'])->name('inquiry.progress.update');

// Jetstream auth dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
<<<<<<< HEAD
});
=======
});

// Optional routes
Route::get('/home', [AuthController::class, 'home'])->name('home');

Route::middleware(['multiauth'])->get('/test-sidebar', function () {
    return view('test-sidebar');
})->name('test.sidebar');
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
