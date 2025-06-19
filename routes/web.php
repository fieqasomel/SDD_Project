<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\MCMCController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReportController;

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
// Debug Routes
// Debug Routes
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
Route::get('/login-simple', fn() => view('auth.login-simple'));

// Debug login page
Route::get('/login-debug', fn() => view('auth.login-debug'));

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

Route::get('/register1', [PublicUserController::class, 'PublicUserRegistration'])->name('registration');

Route::post('/register/publicuser', [AuthController::class, 'registerPublicUser'])->name('register.publicuser.submit');
Route::post('/register/agency', [AuthController::class, 'registerAgency'])->name('register.agency.submit');
Route::post('/register/mcmc', [AuthController::class, 'registerMCMC'])->name('register.mcmc.submit');

// Dashboards
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
});

Route::middleware('auth:publicuser')->group(function () {
    Route::get('/publicuser/dashboard', [PublicUserController::class, 'dashboard'])->name('publicuser.dashboard');
});

Route::middleware('auth:agency')->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'dashboard'])->name('agency.dashboard');
});

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

    // Users
    Route::get('/mcmc/users', [MCMCController::class, 'viewAllUsers'])->name('mcmc.users.index');
    Route::get('/mcmc/users/{user}', [MCMCController::class, 'viewUserDetails'])->name('mcmc.users.show');

    // Reports
    Route::get('/mcmc/reports', [MCMCController::class, 'generateUserReport'])->name('mcmc.reports.index');
    Route::get('/mcmc/reports/download', [MCMCController::class, 'downloadUserReport'])->name('mcmc.reports.download');

    // Logs
    Route::get('/mcmc/activity-logs', [MCMCController::class, 'viewActivityLogs'])->name('mcmc.activity.index');

    // Inquiries (Management) - MCMC Only
    Route::get('/mcmc/inquiries/{id}/details', [InquiryController::class, 'getInquiryDetails'])->name('mcmc.inquiries.details');
    Route::post('/mcmc/inquiries/{id}/quick-action', [InquiryController::class, 'quickAction'])->name('mcmc.inquiries.quick-action');
    Route::get('/mcmc/inquiries/new', [InquiryController::class, 'mcmcNewInquiries'])->name('mcmc.inquiries.new');
    Route::get('/mcmc/inquiries/processed', [InquiryController::class, 'mcmcProcessedInquiries'])->name('mcmc.inquiries.processed');
    Route::get('/mcmc/inquiries/{id}', [InquiryController::class, 'show'])->name('mcmc.inquiries.show');
    Route::get('/mcmc/inquiries/{id}/filter', [InquiryController::class, 'filterInquiry'])->name('mcmc.inquiries.filter');
    Route::post('/mcmc/inquiries/{id}/process', [InquiryController::class, 'processInquiryFilter'])->name('mcmc.inquiries.process');
    Route::post('/mcmc/inquiries/{id}/validate', [InquiryController::class, 'update'])->name('mcmc.inquiries.validate');
    Route::delete('/mcmc/inquiries/{id}', [InquiryController::class, 'destroy'])->name('mcmc.inquiries.delete');

    // Reports and Audit - MCMC Only
    Route::get('/mcmc/inquiry-reports', [InquiryController::class, 'generateMCMCReport'])->name('mcmc.inquiry-reports.generate');
    Route::post('/mcmc/inquiry-reports/pdf', [InquiryController::class, 'generateMCMCReport'])->name('mcmc.inquiry-reports.pdf');
    Route::post('/mcmc/inquiry-reports/excel', [InquiryController::class, 'generateMCMCReport'])->name('mcmc.inquiry-reports.excel');


    Route::get('/mcmc/inquiry-activity', [InquiryController::class, 'history'])->name('mcmc.inquiry-activity.index');
});

// Inquiry Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('inquiries/public', [InquiryController::class, 'publicInquiries'])->name('inquiries.public');
    Route::get('inquiries/search', [InquiryController::class, 'search'])->name('inquiries.search');
    Route::get('inquiries/report', [InquiryController::class, 'generateReport'])->name('inquiries.report');
    Route::get('inquiries/history', [InquiryController::class, 'inquiryHistory'])->name('inquiries.history');
    Route::get('inquiries/{id}/delete', [InquiryController::class, 'delete'])->name('inquiries.delete');
    Route::resource('inquiries', InquiryController::class);
});

// Individual middleware (optional)
Route::group(['middleware' => ['auth:publicuser']], function () {
    Route::prefix('publicuser')->group(function () {
        Route::get('inquiries', [InquiryController::class, 'index'])->name('publicuser.inquiries');
        Route::get('inquiries/create', [InquiryController::class, 'create'])->name('publicuser.inquiries.create');
        Route::get('assignments', [PublicUserController::class, 'myAssignments'])->name('publicuser.assignments');
    });
});

// Assignment Routes
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('assignments', [ComplaintController::class, 'index'])->name('assignments.index');
    Route::get('assignments/report', [ComplaintController::class, 'generateAssignedReport'])->name('assignments.report');
    Route::get('assignments/{inquiry}/assign', [ComplaintController::class, 'assignInquiry'])->name('assignments.assign');
    Route::post('assignments/{inquiry}/assign', [ComplaintController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('assignments/{complaint}/reassign', [ComplaintController::class, 'reassignInquiry'])->name('assignments.reassign');
    Route::post('assignments/{complaint}/reassign', [ComplaintController::class, 'storeReassignment'])->name('assignments.storeReassignment');
    Route::get('assignments/rejected', [ComplaintController::class, 'rejectedAssignments'])->name('assignments.rejected');
    Route::get('assignments/{complaint}/review', [ComplaintController::class, 'reviewInquiry'])->name('assignments.review');
    Route::post('assignments/{complaint}/review', [ComplaintController::class, 'updateReview'])->name('assignments.updateReview');
    Route::get('assignments/{complaint}/verify', [ComplaintController::class, 'verifyAssignment'])->name('assignments.verify');
    Route::post('assignments/{complaint}/verify', [ComplaintController::class, 'processVerification'])->name('assignments.processVerification');
    Route::get('assignments/{complaint}/view', [ComplaintController::class, 'viewAssignedInquiry'])->name('assignments.view');
    Route::get('assignments/{complaint}/history', [ComplaintController::class, 'trackAssignmentHistory'])->name('assignments.history');
    Route::get('assignments/notifications', [ComplaintController::class, 'viewNotifications'])->name('assignments.notifications');
    Route::get('assignments/notifications/{notification}/read', [ComplaintController::class, 'markNotificationAsRead'])->name('assignments.markNotificationRead');
});

// Inquiry Progress
Route::middleware(['auth:publicuser,agency,mcmc'])->group(function () {
    Route::get('/inquiry/{id}/progress/view', [ProgressController::class, 'view'])->name('inquiry.progress.view');
});

Route::middleware(['auth:agency,mcmc'])->group(function () {
    Route::get('/inquiry/{id}/progress/edit', [ProgressController::class, 'edit'])->name('inquiry.progress.edit');
    Route::post('/inquiry/{id}/progress/update', [ProgressController::class, 'update'])->name('inquiry.progress.update');
});

Route::middleware(['auth:publicuser,mcmc'])->group(function () {
    Route::get('/inquiries/search-by-status', [ProgressController::class, 'searchInquiriesByStatus'])->name('inquiries.search.status');
});

Route::middleware(['auth:mcmc'])->group(function () {
    Route::get('/report/progress', [ReportController::class, 'generateProgressReport'])->name('report.progress');
});

// Feedback and Public View
Route::get('/agency/feedback', [ProgressController::class, 'showFeedbackForm'])->name('feedback.form');
Route::post('/agency/feedback/submit', [ProgressController::class, 'submitFeedback'])->name('submit.feedback');
Route::get('/mcmc/alerts', [ProgressController::class, 'viewMcmcAlerts'])->name('mcmc.alerts');
Route::get('/progress-view', [ProgressController::class, 'publicView'])->withoutMiddleware(['auth']);
Route::post('/update-inquiry-progress/{id}', [ProgressController::class, 'update'])->name('inquiry.progress.update.public');

// Jetstream Auth
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // Check PublicUser guard
        if (Auth::guard('publicuser')->check()) {
            return redirect()->route('publicuser.dashboard');
        }
        
        // Check Agency guard
        if (Auth::guard('agency')->check()) {
            return redirect()->route('agency.dashboard');
        }
        
        // Check MCMC guard
        if (Auth::guard('mcmc')->check()) {
            return redirect()->route('mcmc.dashboard');
        }
        
        // Check default auth guard (fallback)
        if (Auth::check()) {
            $user = Auth::user();
            
            // Try to determine type based on user attributes
            if (isset($user->PU_Name)) {
                return redirect()->route('publicuser.dashboard');
            } elseif (isset($user->A_Name)) {
                return redirect()->route('agency.dashboard');
            } elseif (isset($user->M_Name)) {
                return redirect()->route('mcmc.dashboard');
            }
        }
        
        // If no user type can be determined, redirect to login
        return redirect()->route('login')->with('message', 'Please log in to access your dashboard.');
    })->name('dashboard');
});

// Optional
Route::middleware(['multiauth'])->get('/test-sidebar', fn() => view('test-sidebar'))->name('test.sidebar');
