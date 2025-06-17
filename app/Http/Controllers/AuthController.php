<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['home']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('home');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate based on user type
        if ($request->user_type === 'public_user') {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'user_type' => 'required|in:public_user,agency,mcmc'
            ]);
        } else {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'user_type' => 'required|in:public_user,agency,mcmc'
            ]);
        }

        $password = $request->password;
        $userType = $request->user_type;

        $user = null;
        $guard = null;

        switch ($userType) {
            case 'public_user':
                $email = $request->email;
                $user = PublicUser::where('PU_Email', $email)->first();
                $guard = 'publicuser';
                break;
            case 'agency':
                $username = $request->username;
                $user = Agency::where('A_userName', $username)->first();
                $guard = 'agency';
                break;
            case 'mcmc':
                $username = $request->username;
                $user = MCMC::where('M_userName', $username)->first();
                $guard = 'mcmc';
                break;
        }

        if ($user && Hash::check($password, $user->getAuthPassword())) {
            Auth::guard($guard)->login($user);
            
            // Redirect to appropriate dashboard
            switch ($userType) {
                case 'public_user':
                    return redirect()->route('publicuser.dashboard');
                case 'agency':
                    return redirect()->route('agency.dashboard');
                case 'mcmc':
                    return redirect()->route('mcmc.dashboard');
            }
        }

        // Return appropriate error message based on user type
        if ($userType === 'public_user') {
            return back()->withErrors([
                'email' => 'The provided email and password do not match our records.',
            ])->withInput();
        } else {
            return back()->withErrors([
                'username' => 'The provided username and password do not match our records.',
            ])->withInput();
        }
    }

    // Show registration selection
    public function showRegistrationSelection()
    {
        return view('auth.register-selection');
    }

    // Show specific registration forms
    public function showPublicUserRegistration()
    {
        return view('auth.register-publicuser');
    }

    public function showAgencyRegistration()
    {
        return view('auth.register-agency');
    }

    public function showMCMCRegistration()
    {
        return view('auth.register-mcmc');
    }

    // Handle Public User Registration
    public function registerPublicUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ic' => 'required|string|max:20|unique:publicuser,PU_IC',
            'age' => 'required|integer|min:1|max:120',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:50|unique:publicuser,PU_Email',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Generate unique ID
        $lastUser = PublicUser::orderBy('PU_ID', 'desc')->first();
        $newId = $lastUser ? 'PU' . str_pad((intval(substr($lastUser->PU_ID, 2)) + 1), 5, '0', STR_PAD_LEFT) : 'PU00001';

        PublicUser::create([
            'PU_ID' => $newId,
            'PU_Name' => $request->name,
            'PU_IC' => $request->ic,
            'PU_Age' => $request->age,
            'PU_Address' => $request->address,
            'PU_Email' => $request->email,
            'PU_PhoneNum' => $request->phone,
            'PU_Gender' => $request->gender,
            'PU_Password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Handle Agency Registration
    public function registerAgency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:10|unique:agency,A_userName',
            'address' => 'required|string|max:225',
            'email' => 'required|string|email|max:50|unique:agency,A_Email',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Generate unique ID
        $lastAgency = Agency::orderBy('A_ID', 'desc')->first();
        $newId = $lastAgency ? 'A' . str_pad((intval(substr($lastAgency->A_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'A000001';

        Agency::create([
            'A_ID' => $newId,
            'A_Name' => $request->name,
            'A_userName' => $request->username,
            'A_Address' => $request->address,
            'A_Email' => $request->email,
            'A_PhoneNum' => $request->phone,
            'A_Category' => $request->category,
            'A_Password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Handle MCMC Registration
    public function registerMCMC(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:10|unique:mcmc,M_userName',
            'address' => 'required|string|max:225',
            'email' => 'required|string|email|max:50|unique:mcmc,M_Email',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Generate unique ID
        $lastMCMC = MCMC::orderBy('M_ID', 'desc')->first();
        $newId = $lastMCMC ? 'M' . str_pad((intval(substr($lastMCMC->M_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'M000001';

        MCMC::create([
            'M_ID' => $newId,
            'M_Name' => $request->name,
            'M_userName' => $request->username,
            'M_Address' => $request->address,
            'M_Email' => $request->email,
            'M_PhoneNum' => $request->phone,
            'M_Position' => $request->position,
            'M_Password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Logout
    public function logout(Request $request)
    {
        if (Auth::guard('publicuser')->check()) {
            Auth::guard('publicuser')->logout();
        } elseif (Auth::guard('agency')->check()) {
            Auth::guard('agency')->logout();
        } elseif (Auth::guard('mcmc')->check()) {
            Auth::guard('mcmc')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}