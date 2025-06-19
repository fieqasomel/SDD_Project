{{-- 
    COMBINED ASSIGNMENT MANAGEMENT VIEW
    This file serves three types of users with different views:
    - Public Users: See their own assignments (My Assignments)
    - Agency Users: See pending inquiries assigned to them for verification
    - MCMC Users: See administrative assignment management view
--}}
@extends('layouts.app')

@section('title', 'Manage Assignments')

@section('content')
{{-- Check if user is a Public User, Agency User, or MCMC staff and show appropriate view --}}
@if(auth()->user() && get_class(auth()->user()) === 'App\Models\PublicUser')
    {{-- PUBLIC USER VIEW - My Assignments --}}
    <div class="px-6 py-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Assignments</h2>
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Track your submitted inquiries and their assignment status
            </div>
        </div>

        <!-- Public User Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['total_inquiries'] }}</h3>
                <p class="text-sm">Total Inquiries</p>
            </div>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['assigned'] }}</h3>
                <p class="text-sm">Assigned</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['pending'] }}</h3>
                <p class="text-sm">Pending</p>
            </div>
            <div class="bg-blue-200 text-blue-900 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['in_progress'] }}</h3>
                <p class="text-sm">In Progress</p>
            </div>
            <div class="bg-green-200 text-green-900 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['resolved'] }}</h3>
                <p class="text-sm">Resolved</p>
            </div>
            <div class="bg-gray-200 text-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['closed'] }}</h3>
                <p class="text-sm">Closed</p>
            </div>
        </div>

        <!-- Public User Search and Filter -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" action="{{ route('publicuser.assignments') }}" class="grid md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Search by title, description, or ID..."
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="agency" class="block text-sm font-medium text-gray-700">Agency</label>
                    <select name="agency" id="agency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Agencies</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>
                                {{ $agency->A_Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </form>
            <div class="mt-4 pt-4 border-t">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('publicuser.assignments') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded">
                            <i class="fas fa-times mr-2"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Public User Assignments Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            @if($assignments->isEmpty())
                <div class="p-8 text-center text-gray-600">
                    <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-400"></i>
                    <h3 class="text-lg font-medium mb-2">No Assignments Found</h3>
                    <p class="mb-4">You don't have any assigned inquiries yet, or none match your current filters.</p>
                    <a href="{{ route('publicuser.inquiries.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                        <i class="fas fa-plus mr-2"></i> Submit New Inquiry
                    </a>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Since</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignments as $inquiry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Str::limit($inquiry->I_Title, 50) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $inquiry->I_ID }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $inquiry->I_Category }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Submitted: {{ $inquiry->I_Date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $inquiry->complaint->agency->A_Name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ is_array($inquiry->complaint->agency->A_Category) ? implode(', ', $inquiry->complaint->agency->A_Category) : $inquiry->complaint->agency->A_Category }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Assigned by: {{ $inquiry->complaint->mcmc->M_Name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $inquiry->complaint->C_AssignedDate->format('d M Y') }}
                                <div class="text-xs text-gray-500">
                                    {{ $inquiry->complaint->C_AssignedDate->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($inquiry->I_Status) {
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'In Progress' => 'bg-blue-100 text-blue-800',
                                        'Resolved' => 'bg-green-100 text-green-800',
                                        'Closed' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $inquiry->I_Status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $verificationStatus = $inquiry->complaint->C_VerificationStatus ?? 'Pending';
                                    $verificationColor = match($verificationStatus) {
                                        'Verified' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $verificationColor }}">
                                    {{ $verificationStatus }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php 
                                    $days = $inquiry->complaint->C_AssignedDate->diffInDays(now());
                                    $daysColor = $days > 7 ? 'bg-red-100 text-red-800' : ($days > 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $daysColor }}">
                                    {{ $days }} {{ $days == 1 ? 'day' : 'days' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                @if($inquiry->complaint)
                                    <a href="{{ route('assignments.history', $inquiry->complaint->C_ID) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded">
                                        <i class="fas fa-history mr-1"></i> History
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Help Text for Public Users -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">About My Assignments</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>This page shows all your inquiries that have been assigned to agencies by MCMC</li>
                            <li>You can track the status and progress of each assigned inquiry</li>
                            <li>Use the filters above to search for specific assignments</li>
                            <li>Click "View" to see detailed information about your inquiry</li>
                            <li>Click "History" to see the complete assignment and processing history</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif(auth()->user() && get_class(auth()->user()) === 'App\Models\Agency')
    {{-- AGENCY USER VIEW - Inquiries Assigned by MCMC for Verification --}}
    <div class="px-6 py-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">MCMC Assigned Inquiries</h2>
            <div class="text-sm text-gray-600">
                <i class="fas fa-clipboard-check mr-1"></i>
                Verify inquiries forwarded by MCMC to {{ auth()->user()->A_Name }}
            </div>
        </div>

        <!-- Agency Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['total_assigned_to_agency'] ?? 0 }}</h3>
                <p class="text-sm">Assigned by MCMC</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['awaiting_verification'] ?? 0 }}</h3>
                <p class="text-sm">Awaiting Review</p>
            </div>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['verified_by_agency'] ?? 0 }}</h3>
                <p class="text-sm">Verified</p>
            </div>
            <div class="bg-red-100 text-red-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['rejected_by_agency'] ?? 0 }}</h3>
                <p class="text-sm">Rejected</p>
            </div>
            <div class="bg-orange-100 text-orange-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['returned_to_mcmc'] ?? 0 }}</h3>
                <p class="text-sm">Returned to MCMC</p>
            </div>
        </div>

        <!-- Agency Search and Filter -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" action="{{ route('assignments.index') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Search by title, description, or ID..."
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Verification Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </form>
            <div class="mt-4 pt-4 border-t">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded">
                            <i class="fas fa-times mr-2"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiries Assigned by MCMC for Verification -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            @if(isset($assignedInquiries) && $assignedInquiries->isEmpty())
                <div class="p-8 text-center text-gray-600">
                    <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
                    <h3 class="text-lg font-medium mb-2">No Inquiries Assigned</h3>
                    <p class="mb-4">MCMC has not assigned any inquiries to your agency for verification at this time.</p>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>You will see inquiries here when MCMC forwards them to your agency for verification.</p>
                    </div>
                </div>
            @else
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <h3 class="text-lg font-semibold text-blue-900">Inquiries Forwarded by MCMC</h3>
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ isset($assignedInquiries) ? $assignedInquiries->count() : 0 }} pending verification
                        </span>
                    </div>
                    <p class="text-sm text-blue-700 mt-1">
                        These inquiries have been assigned to your agency by MCMC for verification of jurisdiction and scope.
                    </p>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned by MCMC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Since Assignment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($assignedInquiries))
                            @foreach($assignedInquiries as $inquiry)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($inquiry->inquiry->I_Title, 50) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ID: {{ $inquiry->inquiry->I_ID }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ Str::limit($inquiry->inquiry->I_Description, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $inquiry->inquiry->publicUser->PU_Name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $inquiry->inquiry->publicUser->PU_Email ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        Submitted: {{ $inquiry->inquiry->I_Date->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $inquiry->C_AssignedDate->format('d M Y') }}
                                    <div class="text-xs text-gray-500">
                                        {{ $inquiry->C_AssignedDate->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        MCMC Staff: {{ $inquiry->mcmc->M_Name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $inquiry->inquiry->I_Category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $verificationStatus = $inquiry->C_VerificationStatus ?? 'Pending';
                                        $verificationColor = match($verificationStatus) {
                                            'Verified' => 'bg-green-100 text-green-800',
                                            'Rejected' => 'bg-red-100 text-red-800',
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $verificationColor }}">
                                        {{ $verificationStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php 
                                        $days = $inquiry->C_AssignedDate->diffInDays(now());
                                        $daysColor = $days > 7 ? 'bg-red-100 text-red-800' : ($days > 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $daysColor }}">
                                        {{ $days }} {{ $days == 1 ? 'day' : 'days' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex flex-col space-y-2">
                                        <!-- View Inquiry Details -->
                                        <a href="{{ route('inquiries.show', $inquiry->inquiry->I_ID) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">
                                            <i class="fas fa-eye mr-1"></i> View Details
                                        </a>
                                        
                                        @if(($inquiry->C_VerificationStatus ?? 'Pending') === 'Pending')
                                            <!-- Verify Button -->
                                            <button type="button" 
                                                    onclick="showVerifyModal({{ $inquiry->C_ID }}, '{{ $inquiry->inquiry->I_ID }}', '{{ addslashes($inquiry->inquiry->I_Title) }}')"
                                                    class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded">
                                                <i class="fas fa-check mr-1"></i> Verify
                                            </button>
                                            
                                            <!-- Reject Button -->
                                            <button type="button" 
                                                    onclick="showRejectModal({{ $inquiry->C_ID }}, '{{ $inquiry->inquiry->I_ID }}', '{{ addslashes($inquiry->inquiry->I_Title) }}')"
                                                    class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        @else
                                            <!-- Already processed badge -->
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                <i class="fas fa-check-circle mr-1"></i> Processed
                                            </span>
                                            
                                            @if($inquiry->C_VerificationStatus === 'Rejected' && $inquiry->C_RejectionReason)
                                                <button type="button" 
                                                        onclick="showReason('{{ addslashes($inquiry->C_RejectionReason) }}', '{{ $inquiry->inquiry->I_ID }}')"
                                                        class="inline-flex items-center px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded">
                                                    <i class="fas fa-info-circle mr-1"></i> View Reason
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                
                <!-- Pagination -->
                @if(isset($assignedInquiries))
                    <div class="px-6 py-4 border-t">
                        {{ $assignedInquiries->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Help Text for Agency Users -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">MCMC Assignment Verification Process</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>MCMC Forwarded:</strong> These inquiries have been specifically assigned to your agency by MCMC staff</li>
                            <li><strong>Verify Jurisdiction:</strong> Review each inquiry to determine if it falls under your agency's scope and authority</li>
                            <li><strong>Accept & Proceed:</strong> If the inquiry is within your jurisdiction, click "Verify" to accept and begin processing</li>
                            <li><strong>Reject & Return:</strong> If the inquiry is outside your scope, click "Reject" with a detailed explanation</li>
                            <li><strong>Return to MCMC:</strong> Rejected inquiries will be returned to MCMC for reassignment to the appropriate agency</li>
                            <li><strong>Timely Review:</strong> Please respond promptly to maintain efficient inquiry processing workflow</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verify Inquiry Modal -->
    <div id="verifyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        Verify Inquiry
                    </h3>
                    <button type="button" onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="verifyForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Inquiry ID:
                        </label>
                        <p id="verifyInquiryId" class="text-sm text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Inquiry Title:
                        </label>
                        <p id="verifyInquiryTitle" class="text-sm text-gray-900 bg-gray-100 px-3 py-2 rounded"></p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            By verifying this inquiry, you confirm that it falls under your agency's scope and you will proceed with processing it.
                        </p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" 
                            onclick="closeVerifyModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                        <i class="fas fa-check mr-2"></i>Verify Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Inquiry Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                        Reject Inquiry
                    </h3>
                    <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Inquiry ID:
                        </label>
                        <p id="rejectInquiryId" class="text-sm text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Inquiry Title:
                        </label>
                        <p id="rejectInquiryTitle" class="text-sm text-gray-900 bg-gray-100 px-3 py-2 rounded"></p>
                    </div>
                    <div class="mb-4">
                        <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection: <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" 
                                  id="rejectionReason" 
                                  rows="4" 
                                  required
                                  placeholder="Please provide a detailed explanation of why this inquiry doesn't fall under your agency's scope..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">This reason will be forwarded to MCMC for reassignment consideration.</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            By rejecting this inquiry, it will be returned to MCMC with your reason for reassignment to the correct agency.
                        </p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                        <i class="fas fa-times mr-2"></i>Reject Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Reason Modal -->
    <div id="reasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-orange-600 mr-2"></i>
                        Rejection Reason
                    </h3>
                    <button type="button" onclick="closeReasonModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Inquiry ID:
                    </label>
                    <p id="reasonInquiryId" class="text-sm text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection:
                    </label>
                    <div id="reasonText" class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-sm text-orange-800 min-h-[100px] max-h-[300px] overflow-y-auto whitespace-pre-wrap"></div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-2xl">
                <button type="button" 
                        onclick="closeReasonModal()"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>

@else
    {{-- MCMC STAFF VIEW - Administrative Assignment Management --}}
    <div class="px-6 py-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Assignment Management</h2>
            <a href="{{ route('assignments.report') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                <i class="fas fa-chart-bar mr-2"></i> Generate Report
            </a>
        </div>

        <!-- MCMC Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['total_assignments'] }}</h3>
                <p>Total Assignments</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['pending'] }}</h3>
                <p>Pending</p>
            </div>
            <div class="bg-blue-200 text-blue-900 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['in_progress'] }}</h3>
                <p>In Progress</p>
            </div>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['resolved'] }}</h3>
                <p>Resolved</p>
            </div>
            <div class="bg-gray-200 text-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['closed'] }}</h3>
                <p>Closed</p>
            </div>
            <div class="bg-green-200 text-green-900 p-4 rounded-lg shadow">
                <h3 class="text-xl font-bold">{{ $stats['this_month'] }}</h3>
                <p>This Month</p>
            </div>
        </div>

        <!-- Unassigned Inquiries Section -->
        @if($unassignedInquiries->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-bell mr-2 text-orange-600"></i>Unassigned Inquiries 
                    <span class="ml-2 bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $unassignedInquiries->count() }} pending</span>
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($unassignedInquiries as $inquiry)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($inquiry->I_Title, 50) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($inquiry->publicUser)
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-900">{{ $inquiry->publicUser->PU_Name }}</span>
                                                <span class="text-xs text-gray-500">{{ $inquiry->publicUser->PU_Email }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">User not found</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $inquiry->I_Category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $inquiry->I_Date ? date('d M Y', strtotime($inquiry->I_Date)) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- View Button -->
                                            <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                               title="View inquiry details">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                            
                                            <!-- Assign Button -->
                                            <a href="{{ route('assignments.assign', $inquiry->I_ID) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                               title="Assign this inquiry to an agency">
                                                <i class="fas fa-user-plus mr-1"></i>Assign
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($unassignedInquiries->count() >= 10)
                    <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                        <a href="{{ route('inquiries.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-arrow-right mr-2"></i>View all unassigned inquiries
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- MCMC Search and Filter -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" action="{{ route('assignments.index') }}" class="grid md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="agency" class="block text-sm font-medium text-gray-700">Agency</label>
                    <select name="agency" id="agency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Agencies</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>
                                {{ $agency->A_Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <!-- MCMC Assignments Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            @if($assignments->isEmpty())
                <div class="p-6 text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    No assignments found. 
                    <a href="{{ route('inquiries.index') }}" class="text-blue-600 hover:underline">View unassigned inquiries</a> to create new assignments.
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignment ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inquiry Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verification</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $assignment->C_ID }}<br>
                                <span class="text-xs text-gray-500">{{ $assignment->inquiry->I_ID }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium">{{ Str::limit($assignment->inquiry->I_Title, 40) }}</div>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $assignment->inquiry->I_Category }}</span><br>
                                <span class="text-xs text-gray-500">by {{ $assignment->inquiry->publicUser->PU_Name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium">{{ $assignment->agency->A_Name }}</div>
                                <span class="text-xs text-gray-500">{{ is_array($assignment->agency->A_Category) ? implode(', ', $assignment->agency->A_Category) : $assignment->agency->A_Category }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $assignment->C_AssignedDate->format('d M Y') }}<br>
                                <span class="text-xs text-gray-500">by {{ $assignment->mcmc->M_Name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="text-xs inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                    {{ $assignment->inquiry->I_Status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="text-xs inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                    {{ $assignment->C_VerificationStatus ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php $days = $assignment->C_AssignedDate->diffInDays(now()); @endphp
                                <span class="text-xs px-2 py-1 rounded {{ $days > 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $days }} days
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center space-x-1">
                                    <!-- View Assignment -->
                                    <a href="{{ route('assignments.view', $assignment->C_ID) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                       title="View assignment details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- History -->
                                    <a href="{{ route('assignments.history', $assignment->C_ID) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold rounded shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                       title="View assignment history">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    
                                    <!-- Reassign Button (only show if rejected) -->
                                    @if($assignment->C_VerificationStatus === 'Rejected')
                                        <a href="{{ route('assignments.reassign', $assignment->C_ID) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                           title="Reassign to another agency">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                        
                                        <!-- View Rejection Reason -->
                                        @if($assignment->C_RejectionReason)
                                            <button type="button" 
                                                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                                    onclick="showRejectionReason({{ json_encode($assignment->C_RejectionReason) }}, '{{ $assignment->inquiry->I_ID }}')"
                                                    title="View rejection reason">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Rejection Reason Modal -->
<div id="rejectionReasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Assignment Rejection Reason
                </h3>
                <button type="button" onclick="closeRejectionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Inquiry ID:
                </label>
                <p id="modalInquiryId" class="text-sm text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Rejection:
                </label>
                <div id="modalRejectionReason" class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800 min-h-[100px] max-h-[300px] overflow-y-auto whitespace-pre-wrap"></div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-2xl">
            <button type="button" 
                    onclick="closeRejectionModal()"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>Close
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const agencySelect = document.getElementById('agency');
    if (agencySelect) {
        agencySelect.addEventListener('change', function () {
            this.form.submit();
        });
    }
});

// Function to show rejection reason modal
function showRejectionReason(reason, inquiryId) {
    const modal = document.getElementById('rejectionReasonModal');
    const modalInquiryId = document.getElementById('modalInquiryId');
    const modalRejectionReason = document.getElementById('modalRejectionReason');
    
    modalInquiryId.textContent = inquiryId;
    modalRejectionReason.textContent = reason;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Prevent body scrolling when modal is open
    document.body.style.overflow = 'hidden';
}

// Function to close rejection reason modal
function closeRejectionModal() {
    const modal = document.getElementById('rejectionReasonModal');
    
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Restore body scrolling
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('rejectionReasonModal');
    const modalContent = modal.querySelector('.bg-white');
    
    if (event.target === modal && !modalContent.contains(event.target)) {
        closeRejectionModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRejectionModal();
        closeVerifyModal();
        closeRejectModal();
        closeReasonModal();
    }
});

// Agency Verify Modal Functions
function showVerifyModal(complaintId, inquiryId, inquiryTitle) {
    const modal = document.getElementById('verifyModal');
    const form = document.getElementById('verifyForm');
    const inquiryIdElement = document.getElementById('verifyInquiryId');
    const inquiryTitleElement = document.getElementById('verifyInquiryTitle');
    
    inquiryIdElement.textContent = inquiryId;
    inquiryTitleElement.textContent = inquiryTitle;
    form.action = `/agency/assignments/${complaintId}/verify`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeVerifyModal() {
    const modal = document.getElementById('verifyModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Agency Reject Modal Functions
function showRejectModal(complaintId, inquiryId, inquiryTitle) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const inquiryIdElement = document.getElementById('rejectInquiryId');
    const inquiryTitleElement = document.getElementById('rejectInquiryTitle');
    const reasonTextarea = document.getElementById('rejectionReason');
    
    inquiryIdElement.textContent = inquiryId;
    inquiryTitleElement.textContent = inquiryTitle;
    form.action = `/agency/assignments/${complaintId}/reject`;
    reasonTextarea.value = '';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Agency View Reason Modal Functions
function showReason(reason, inquiryId) {
    const modal = document.getElementById('reasonModal');
    const inquiryIdElement = document.getElementById('reasonInquiryId');
    const reasonTextElement = document.getElementById('reasonText');
    
    inquiryIdElement.textContent = inquiryId;
    reasonTextElement.textContent = reason;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeReasonModal() {
    const modal = document.getElementById('reasonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    // Verify Modal
    const verifyModal = document.getElementById('verifyModal');
    if (verifyModal && event.target === verifyModal) {
        closeVerifyModal();
    }
    
    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal && event.target === rejectModal) {
        closeRejectModal();
    }
    
    // Reason Modal
    const reasonModal = document.getElementById('reasonModal');
    if (reasonModal && event.target === reasonModal) {
        closeReasonModal();
    }
});
</script>
@endsection