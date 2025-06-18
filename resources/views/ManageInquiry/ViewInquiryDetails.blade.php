@extends('layouts.app')

@section('title', 'View Inquiry Details - MySebenarnya System')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Inquiry Details</h1>
                <p class="text-gray-600">View complete inquiry information and status</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                {{-- MCMC specific actions --}}
                @if(Auth::guard('mcmc')->check())
                    @if($inquiry->I_Status === 'Pending')
                        <a href="{{ route('mcmc.inquiries.filter', $inquiry->I_ID) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-filter mr-2"></i>Process Inquiry
                        </a>
                    @endif
                    @if($inquiry->I_Status === 'Approved' && $inquiry->complaints->count() === 0)
                        <a href="{{ route('assignments.assign', $inquiry->I_ID) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-building mr-2"></i>Assign to Agency
                        </a>
                    @endif
                    <a href="{{ route('mcmc.inquiries.new') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to New Inquiries
                    </a>
                {{-- Public User specific actions --}}
                @elseif(Auth::guard('publicuser')->check())
                    @if($inquiry->I_Status === 'Pending')
                        <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('inquiries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to My Inquiries
                    </a>
                {{-- Default actions --}}
                @else
                    <a href="{{ route('inquiries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                @endif
            </div>
        </div>

        <!-- Inquiry Details Card -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>Inquiry Information
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Inquiry ID & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Inquiry ID</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $inquiry->I_ID }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Status</label>
                        @php
                            $statusColors = [
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'In Progress' => 'bg-blue-100 text-blue-800',
                                'Resolved' => 'bg-green-100 text-green-800',
                                'Closed' => 'bg-gray-100 text-gray-800',
                                'Rejected' => 'bg-red-100 text-red-800'
                            ];
                            $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $colorClass }}">
                            {{ $inquiry->I_Status }}
                        </span>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Title</label>
                    <p class="text-lg font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-lg p-3">
                        {{ $inquiry->I_Title }}
                    </p>
                </div>

                <!-- Category & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Category</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $inquiry->I_Category }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Date Submitted</label>
                        <p class="text-gray-900 bg-gray-50 border border-gray-200 rounded-lg p-3">
                            {{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('F j, Y') : 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Source -->
                @if($inquiry->I_Source)
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Source</label>
                    <p class="text-gray-900 bg-gray-50 border border-gray-200 rounded-lg p-3">
                        {{ $inquiry->I_Source }}
                    </p>
                </div>
                @endif

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Description</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-line">{{ $inquiry->I_Description }}</p>
                    </div>
                </div>

                <!-- Attachment -->
                @if($inquiry->I_filename)
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Attachment</label>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-paperclip text-2xl text-gray-400"></i>
                        </div>
                        <div>
                            <a href="{{ Storage::url($inquiry->I_filename) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-download mr-2"></i>Download Attachment
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- MCMC Staff Only Section --}}
        @if(Auth::guard('mcmc')->check())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- User Information for MCMC -->
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-indigo-600"></i>Submitter Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-gray-900 font-semibold">{{ $inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                        </div>
                        @if($inquiry->publicUser->PU_Phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $inquiry->publicUser->PU_Phone }}</p>
                        </div>
                        @endif
                        @if($inquiry->publicUser->PU_Address)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900 text-sm">{{ $inquiry->publicUser->PU_Address }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User ID</label>
                            <p class="text-gray-600 text-sm font-mono">{{ $inquiry->publicUser->PU_ID ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MCMC Processing Information -->
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-shield mr-2 text-green-600"></i>MCMC Processing
                    </h3>
                </div>
                <div class="p-6">
                    @if($inquiry->mcmc_processed_by || $inquiry->mcmc_notes)
                    <div class="space-y-4">
                        @if($inquiry->mcmc_processed_by)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Processed By</label>
                            <p class="text-gray-900">{{ $inquiry->mcmcProcessor->M_Name ?? $inquiry->mcmc_processed_by }}</p>
                        </div>
                        @endif
                        @if($inquiry->mcmc_processed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Processed At</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($inquiry->mcmc_processed_at)->format('d M Y, g:i A') }}</p>
                        </div>
                        @endif
                        @if($inquiry->mcmc_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">MCMC Notes</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $inquiry->mcmc_notes }}</p>
                            </div>
                        </div>
                        @endif
                        @if($inquiry->rejection_reason)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-red-900 whitespace-pre-wrap">{{ $inquiry->rejection_reason }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-hourglass-half text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Not yet processed by MCMC</p>
                        @if($inquiry->I_Status === 'Pending')
                        <a href="{{ route('mcmc.inquiries.filter', $inquiry->I_ID) }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>Process Now
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assignment Information -->
            @if($inquiry->complaints->count() > 0)
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-building mr-2 text-purple-600"></i>Agency Assignments
                        </h3>
                    </div>
                    <div class="p-6">
                        @foreach($inquiry->complaints as $complaint)
                        <div class="mb-6 last:mb-0 p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned to Agency</label>
                                    <p class="text-gray-900 font-semibold">{{ $complaint->agency->A_Name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assignment Date</label>
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($complaint->C_AssignedDate)->format('d M Y, g:i A') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($complaint->C_Status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($complaint->C_Status === 'In Progress') bg-blue-100 text-blue-800
                                        @elseif($complaint->C_Status === 'Resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $complaint->C_Status }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Complaint ID</label>
                                    <p class="text-gray-900 font-mono">{{ $complaint->C_ID }}</p>
                                </div>
                            </div>
                            @if($complaint->C_Description)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assignment Notes</label>
                                <p class="text-gray-900 text-sm">{{ $complaint->C_Description }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- System Statistics for MCMC -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg text-white p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>System Statistics
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ \App\Models\Inquiry::count() }}</div>
                            <div class="text-blue-100 text-sm">Total Inquiries</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ \App\Models\Inquiry::where('I_Status', 'Pending')->count() }}</div>
                            <div class="text-blue-100 text-sm">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ \App\Models\Inquiry::whereMonth('I_Date', now()->month)->count() }}</div>
                            <div class="text-blue-100 text-sm">This Month</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ \App\Models\Inquiry::whereDate('I_Date', today())->count() }}</div>
                            <div class="text-blue-100 text-sm">Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Public User Action Buttons --}}
        @if(Auth::guard('publicuser')->check())
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            @if($inquiry->I_Status === 'Pending')
                <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-edit mr-2"></i>Edit Inquiry
                </a>
                
                <form method="POST" action="{{ route('inquiries.destroy', $inquiry->I_ID) }}" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-trash mr-2"></i>Delete Inquiry
                    </button>
                </form>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection