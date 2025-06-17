<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\MCMCController;
<<<<<<< HEAD

=======
>>>>>>> b20c6e3d7e5e3fe9b616e16d447a729303ffcebc
use App\Http\Controllers\InquiryController;

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

Route::post('/register/publicuser', [AuthController::class, 'registerPublicUser'])->name('register.publicuser.submit');
Route::post('/register/agency', [AuthController::class, 'registerAgency'])->name('register.agency.submit');
Route::post('/register/mcmc', [AuthController::class, 'registerMCMC'])->name('register.mcmc.submit');

// Protected Dashboard Routes
Route::middleware('auth:publicuser')->group(function () {
    Route::get('/publicuser/dashboard', [PublicUserController::class, 'dashboard'])->name('publicuser.dashboard');
});

Route::middleware('auth:agency')->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
});

Route::middleware('auth:mcmc')->group(function () {
    Route::get('/mcmc/dashboard', [MCMCController::class, 'dashboard'])->name('mcmc.dashboard');
<<<<<<< HEAD
    
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
    
    // MCMC Inquiry Management Routes - using InquiryController
    Route::get('/mcmc/inquiries/new', [InquiryController::class, 'index'])->name('mcmc.inquiries.new');
    Route::get('/mcmc/inquiries/processed', [InquiryController::class, 'index'])->name('mcmc.inquiries.processed');
    Route::get('/mcmc/inquiries/{id}', [InquiryController::class, 'show'])->name('mcmc.inquiries.show');
    Route::post('/mcmc/inquiries/{id}/validate', [InquiryController::class, 'update'])->name('mcmc.inquiries.validate');
    
    // MCMC Report Generation - using InquiryController
    Route::get('/mcmc/inquiry-reports', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.generate');
    Route::post('/mcmc/inquiry-reports/pdf', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.pdf');
    Route::post('/mcmc/inquiry-reports/excel', [InquiryController::class, 'generateReport'])->name('mcmc.inquiry-reports.excel');
    
    // MCMC Activity Log - using history method
    Route::get('/mcmc/inquiry-activity', [InquiryController::class, 'history'])->name('mcmc.inquiry-activity.index');
=======
>>>>>>> b20c6e3d7e5e3fe9b616e16d447a729303ffcebc
});

// Inquiry Management Routes - Available to all authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    // Special routes that need to come before resource routes
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/history', [InquiryController::class, 'history'])->name('inquiries.history');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
    Route::get('inquiries/{id}/delete', [InquiryController::class, 'delete'])->name('inquiries.delete');
    
    // Resource routes
    Route::resource('inquiries', InquiryController::class);
});

// Assignment Management Routes - Available to all authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
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

<<<<<<< HEAD
// Home route using the AuthController
Route::get('/home', [AuthController::class, 'home'])->name('home');
=======
// Test route for sidebar (you can remove this later)
Route::middleware(['multiauth'])->get('/test-sidebar', function () {
    return view('test-sidebar');
})->name('test.sidebar');
>>>>>>> 847bd712ee5c51c00a5362abdefcc7e763f5e46a
