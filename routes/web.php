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

// Test route for debugging
Route::get('/test-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Test route to update MCMC password
Route::get('/update-mcmc-password', function () {
    $mcmc = \App\Models\MCMC::find('M000001');
    if ($mcmc) {
        $mcmc->M_Password = 'password123';
        $mcmc->save();
        return 'Password updated for ' . $mcmc->M_userName . ' (ID: ' . $mcmc->M_ID . ')';
    }
    return 'MCMC user not found';
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

// Debug login page
Route::get('/login-debug', function () {
    return view('auth.login-debug');
});

// Test POST route to see if form submission works
Route::post('/test-login', function (Request $request) {
    return response()->json([
        'message' => 'Form submission works!',
        'data' => $request->all(),
        'csrf_valid' => true
    ]);
});

// Button test page
Route::get('/test-button', function () {
    return view('auth.test-button');
});

// Test MCMC login page
Route::get('/test-mcmc-login', function () {
    return view('auth.test-mcmc-login');
});

// Test MCMC authentication manually
Route::get('/test-mcmc-auth', function () {
    $username = 'MCMC';
    $password = 'password123';
    
    $user = \App\Models\MCMC::where('M_userName', $username)->first();
    
    $output = '<h2>MCMC Authentication Test</h2>';
    $output .= '<p><strong>Username:</strong> ' . $username . '</p>';
    $output .= '<p><strong>User Found:</strong> ' . ($user ? 'YES' : 'NO') . '</p>';
    
    if ($user) {
        $output .= '<p><strong>User ID:</strong> ' . $user->M_ID . '</p>';
        $output .= '<p><strong>User Name:</strong> ' . $user->M_Name . '</p>';
        $output .= '<p><strong>Password Hash:</strong> ' . substr($user->M_Password, 0, 20) . '...</p>';
        
        $passwordCheck = \Hash::check($password, $user->getAuthPassword());
        $output .= '<p><strong>Password Check:</strong> ' . ($passwordCheck ? 'VALID' : 'INVALID') . '</p>';
        
        if ($passwordCheck) {
            // Try to login
            \Auth::guard('mcmc')->login($user);
            $output .= '<p><strong>Login Attempt:</strong> SUCCESS</p>';
            $output .= '<p><strong>Authenticated:</strong> ' . (\Auth::guard('mcmc')->check() ? 'YES' : 'NO') . '</p>';
            
            if (\Auth::guard('mcmc')->check()) {
                $output .= '<p><a href="/mcmc/dashboard">Go to MCMC Dashboard</a></p>';
            }
        }
    }
    
    return $output;
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

Route::post('/register/publicuser', [AuthController::class, 'registerPublicUser'])->name('register.publicuser.submit');
Route::post('/register/agency', [AuthController::class, 'registerAgency'])->name('register.agency.submit');
Route::post('/register/mcmc', [AuthController::class, 'registerMCMC'])->name('register.mcmc.submit');

// Home route for authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
});

// Protected Dashboard Routes
Route::middleware('auth:publicuser')->group(function () {
    Route::get('/publicuser/dashboard', [PublicUserController::class, 'dashboard'])->name('publicuser.dashboard');
});

Route::middleware('auth:agency')->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
});

Route::middleware('auth:mcmc')->group(function () {
    Route::get('/mcmc/dashboard', [MCMCController::class, 'dashboard'])->name('mcmc.dashboard');
    
    // MCMC Inquiry Management Routes
    Route::get('/mcmc/inquiries/new', [InquiryController::class, 'viewNewInquiries'])->name('inquiries.mcmc.new');
    Route::get('/mcmc/inquiries/{id}/filter', [InquiryController::class, 'filterInquiry'])->name('inquiries.mcmc.filter');
    Route::post('/mcmc/inquiries/{id}/filter', [InquiryController::class, 'processInquiryFilter'])->name('inquiries.mcmc.process');
    Route::get('/mcmc/inquiries/previous', [InquiryController::class, 'viewPreviousInquiries'])->name('inquiries.mcmc.previous');
    Route::get('/mcmc/inquiries/report', [InquiryController::class, 'generateMCMCReport'])->name('inquiries.mcmc.report');
    Route::get('/mcmc/inquiries/audit', [InquiryController::class, 'auditLog'])->name('inquiries.mcmc.audit');
});

// Inquiry Management Routes - Available to all authenticated users
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    // Special routes that need to come before resource routes
    Route::get('inquiries/public', [InquiryController::class, 'publicInquiries'])->name('inquiries.public');
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
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