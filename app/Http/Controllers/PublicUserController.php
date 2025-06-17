<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PublicUserController extends Controller
{
    public function showForm()
    {
        return view('Registration.register_user');
    }

    public function register(Request $request)
    {
        $request->validate([
            'PU_Name' => 'required|string',
            'PU_IC' => 'required|digits:12',
            'PU_Age' => 'required|integer|min:1',
            'PU_Address' => 'required|string',
            'PU_Email' => 'required|email|unique:public_users,PU_Email',
            'PU_PhoneNum' => 'required|digits_between:10,11',
            'PU_Gender' => 'required|in:Male,Female',
            'PU_Password' => 'required|min:6',
        ]);

        // Generate unique PU_ID
        $lastUser = PublicUser::orderBy('PU_ID', 'desc')->first();
        $nextId = 'PU00001';
        if ($lastUser) {
            $num = intval(substr($lastUser->PU_ID, 2)) + 1;
            $nextId = 'PU' . str_pad($num, 5, '0', STR_PAD_LEFT);
        }

        // Store to database
        PublicUser::create([
            'PU_ID' => $nextId,
            'PU_Name' => $request->PU_Name,
            'PU_IC' => $request->PU_IC,
            'PU_Age' => $request->PU_Age,
            'PU_Address' => $request->PU_Address,
            'PU_Email' => $request->PU_Email,
            'PU_PhoneNum' => $request->PU_PhoneNum,
            'PU_Gender' => $request->PU_Gender,
            'PU_Password' => Hash::make($request->PU_Password),
        ]);

        return redirect()->back()->with('success', "Registration successful! Your ID is: $nextId");
    }

    public function dashboard()
    {
        $user = Auth::guard('publicuser')->user();
        
        // Calculate inquiry statistics for the user
        $stats = [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'resolved' => 0
        ];
        
        if ($user) {
            // Get all inquiries for this user
            $inquiries = \App\Models\Inquiry::where('PU_ID', $user->PU_ID)->get();
            
            // Calculate statistics
            $stats['total'] = $inquiries->count();
            $stats['pending'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_PENDING)->count();
            $stats['in_progress'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_IN_PROGRESS)->count();
            $stats['resolved'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_RESOLVED)->count();
        }
        
        return view('Dashboard.PublicUserDashboard', compact('user', 'stats'));
    }
}
