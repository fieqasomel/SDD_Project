<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\MCMCController;
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
});

// Inquiry Management Routes - Available to all authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    // Special routes that need to come before resource routes
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
    
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
