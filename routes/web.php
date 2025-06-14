<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\MCMCController;
use App\Http\Controllers\MCMCInquiryController;
use App\Http\Controllers\InquiryController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Recovery Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationSelection'])->name('register');
Route::get('/register/publicuser', [AuthController::class, 'showPublicUserRegistration'])->name('register.publicuser');
Route::get('/register/agency', [AuthController::class, 'showAgencyRegistration'])->name('register.agency');
Route::get('/register/mcmc', [AuthController::class, 'showMCMCRegistration'])->name('register.mcmc');

Route::post('/register/publicuser', [AuthController::class, 'registerPublicUser'])->name('register.publicuser.submit');
Route::post('/register/agency', [AuthController::class, 'registerAgency'])->name('register.agency.submit');
Route::post('/register/mcmc', [AuthController::class, 'registerMCMC'])->name('register.mcmc.submit');

// Protected Dashboard Routes
Route::middleware('auth:publicuser')->group(function () {
    Route::get('/publicuser/dashboard', [PublicUserController::class, 'dashboard'])->name('publicuser.dashboard');
});

Route::middleware('auth:agency')->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
    
    // Agency Inquiry Management Routes
    Route::get('/agency/inquiries', [AgencyController::class, 'inquiries'])->name('agency.inquiries.index');
    Route::get('/agency/inquiries/history', [AgencyController::class, 'inquiryHistory'])->name('agency.inquiries.history');
    Route::get('/agency/inquiries/{inquiry}', [AgencyController::class, 'showInquiry'])->name('agency.inquiries.show');
    Route::post('/agency/inquiries/{inquiry}/update-status', [AgencyController::class, 'updateInquiryStatus'])->name('agency.inquiries.update-status');
    Route::post('/agency/inquiries/{inquiry}/add-update', [AgencyController::class, 'addInquiryUpdate'])->name('agency.inquiries.add-update');
});

Route::middleware('auth:mcmc')->group(function () {
    Route::get('/mcmc/dashboard', [MCMCController::class, 'dashboard'])->name('mcmc.dashboard');
    
    // Agency Management Routes
    Route::get('/mcmc/agencies', [MCMCController::class, 'manageAgencies'])->name('mcmc.agencies.index');
    Route::get('/mcmc/agencies/create', [MCMCController::class, 'createAgency'])->name('mcmc.agencies.create');
    Route::post('/mcmc/agencies', [MCMCController::class, 'storeAgency'])->name('mcmc.agencies.store');
    Route::get('/mcmc/agencies/{agency}/edit', [MCMCController::class, 'editAgency'])->name('mcmc.agencies.edit');
    Route::put('/mcmc/agencies/{agency}', [MCMCController::class, 'updateAgency'])->name('mcmc.agencies.update');
    Route::delete('/mcmc/agencies/{agency}', [MCMCController::class, 'destroyAgency'])->name('mcmc.agencies.destroy');
    Route::post('/mcmc/agencies/{agency}/reset-password', [MCMCController::class, 'resetAgencyPassword'])->name('mcmc.agencies.reset-password');
    
    // User Data Access Routes
    Route::get('/mcmc/users', [MCMCController::class, 'viewAllUsers'])->name('mcmc.users.index');
    Route::get('/mcmc/users/{user}', [MCMCController::class, 'viewUserDetails'])->name('mcmc.users.show');
    
    // Reporting and Analytics Routes
    Route::get('/mcmc/reports', [MCMCController::class, 'generateUserReport'])->name('mcmc.reports.index');
    Route::get('/mcmc/reports/download', [MCMCController::class, 'downloadUserReport'])->name('mcmc.reports.download');
    
    // Activity Logs
    Route::get('/mcmc/activity-logs', [MCMCController::class, 'viewActivityLogs'])->name('mcmc.activity.index');
    
    // MCMC Inquiry Management Routes
    Route::get('/mcmc/inquiries/new', [MCMCInquiryController::class, 'newInquiries'])->name('mcmc.inquiries.new');
    Route::get('/mcmc/inquiries/processed', [MCMCInquiryController::class, 'processedInquiries'])->name('mcmc.inquiries.processed');
    Route::get('/mcmc/inquiries/{id}', [MCMCInquiryController::class, 'showInquiry'])->name('mcmc.inquiries.show');
    Route::post('/mcmc/inquiries/{id}/validate', [MCMCInquiryController::class, 'validateInquiry'])->name('mcmc.inquiries.validate');
    
    // MCMC Report Generation
    Route::get('/mcmc/inquiry-reports', [MCMCInquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.generate');
    Route::post('/mcmc/inquiry-reports/pdf', [MCMCInquiryController::class, 'exportReportPDF'])->name('mcmc.inquiry-reports.pdf');
    Route::post('/mcmc/inquiry-reports/excel', [MCMCInquiryController::class, 'exportReportExcel'])->name('mcmc.inquiry-reports.excel');
    
    // MCMC Activity Log
    Route::get('/mcmc/inquiry-activity', [MCMCInquiryController::class, 'activityLog'])->name('mcmc.inquiry-activity.index');
});

// Inquiry Management Routes - Available to all authenticated users
Route::middleware(['multiauth'])->group(function () {
    // Special routes that need to come before resource routes
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
    
    // Resource routes
    Route::resource('inquiries', InquiryController::class);
});

// Assignment Management Routes - Available to all authenticated users  
Route::middleware(['multiauth'])->group(function () {
    // Assignment management routes
    Route::get('assignments', [App\Http\Controllers\ComplaintController::class, 'index'])->name('assignments.index');
    Route::get('assignments/report', [App\Http\Controllers\ComplaintController::class, 'generateAssignedReport'])->name('assignments.report');
    
    // Assignment actions (MCMC only)
    Route::get('inquiries/{inquiry}/assign', [App\Http\Controllers\ComplaintController::class, 'assignInquiry'])->name('assignments.assign');
    Route::post('inquiries/{inquiry}/assign', [App\Http\Controllers\ComplaintController::class, 'storeAssignment'])->name('assignments.store');
    
    // View assignment details
    Route::get('assignments/{complaint}/view', [App\Http\Controllers\ComplaintController::class, 'viewAssignedInquiry'])->name('assignments.view');
    Route::get('assignments/{complaint}/history', [App\Http\Controllers\ComplaintController::class, 'trackAssignmentHistory'])->name('assignments.history');
    
    // Reassignment (MCMC only)
    Route::get('assignments/{complaint}/reassign', [App\Http\Controllers\ComplaintController::class, 'reassignInquiry'])->name('assignments.reassign');
    Route::post('assignments/{complaint}/reassign', [App\Http\Controllers\ComplaintController::class, 'storeReassignment'])->name('assignments.storeReassignment');
    
    // Review assignment (Agency only)
    Route::get('assignments/{complaint}/review', [App\Http\Controllers\ComplaintController::class, 'reviewInquiry'])->name('assignments.review');
    Route::post('assignments/{complaint}/review', [App\Http\Controllers\ComplaintController::class, 'updateReview'])->name('assignments.updateReview');
    
    // Verification workflow (Agency only)
    Route::get('assignments/{complaint}/verify', [App\Http\Controllers\ComplaintController::class, 'verifyAssignment'])->name('assignments.verify');
    Route::post('assignments/{complaint}/verify', [App\Http\Controllers\ComplaintController::class, 'processVerification'])->name('assignments.processVerification');
    
    // Rejected assignments (MCMC only)
    Route::get('assignments/rejected', [App\Http\Controllers\ComplaintController::class, 'rejectedAssignments'])->name('assignments.rejected');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
