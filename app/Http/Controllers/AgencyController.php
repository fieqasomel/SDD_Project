<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('agency')->user();
        return view('Dashboard.AgencyDashboard', compact('user'));
    }
}