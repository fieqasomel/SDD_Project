@extends('layouts.app')

@section('title', 'View Assigned Inquiry')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Assigned Inquiry Details</h3>
            <div class="space-x-2">
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assignments
                </a>
                @if($userType === 'mcmc')
                    <a href="{{ route('assignments.reassign', $complaint->C_ID) }}" class="inline-flex items-center px-3 py-1 bg-yellow-400 text-yellow-900 text-sm rounded hover:bg-yellow-500">
                        <i class="fas fa-exchange-alt mr-1"></i> Reassign
                    </a>
                @endif
                <a href="{{ route('assignments.history', $complaint->C_ID) }}" class="inline-flex items-center px-3 py-1 bg-blue-400 text-white text-sm rounded hover:bg-blue-500">
                    <i class="fas fa-history mr-1"></i> View History
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Inquiry Info -->
            <div class="border rounded-lg p-4 shadow-sm">
                <h5 class="text-lg font-medium mb-4 text-blue-600">Inquiry Information</h5>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Inquiry ID:</strong> {{ $complaint->inquiry->I_ID }}</p>
                    <p><strong>Title:</strong> {{ $complaint->inquiry->I_Title }}</p>
                    <p><strong>Category:</strong> <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $complaint->inquiry->I_Category }}</span></p>
                    <p><strong>Status:</strong> <span class="inline-block bg-{{ $complaint->inquiry->getStatusBadgeColor() }}-100 text-{{ $complaint->inquiry->getStatusBadgeColor() }}-800 px-2 py-0.5 rounded">{{ $complaint->inquiry->I_Status }}</span></p>
                    <p><strong>Submitted Date:</strong> {{ $complaint->inquiry->I_Date ? \Carbon\Carbon::parse($complaint->inquiry->I_Date)->format('d M Y') : 'N/A' }}</p>
                    <p><strong>Source:</strong> {{ $complaint->inquiry->I_Source ?? 'N/A' }}</p>
                </div>
                <div class="mt-4">
                    <strong>Description:</strong>
                    <p class="text-gray-600 mt-1">{{ $complaint->inquiry->I_Description }}</p>
                </div>
                @if($complaint->inquiry->I_filename)
                    <div class="mt-4">
                        <strong>Attachment:</strong><br>
                        <a href="{{ Storage::url($complaint->inquiry->InfoPath) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                            <i class="fas fa-download mr-1"></i> {{ $complaint->inquiry->I_filename }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Assignment Info -->
            <div class="border rounded-lg p-4 shadow-sm">
                <h5 class="text-lg font-medium mb-4 text-green-600">Assignment Information</h5>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Assignment ID:</strong> {{ $complaint->C_ID }}</p>
                    <p><strong>Assigned to:</strong> {{ $complaint->agency->A_Name }}</p>
                    <p><strong>Agency Category:</strong> <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded">{{ is_array($complaint->agency->A_Category) ? implode(', ', $complaint->agency->A_Category) : $complaint->agency->A_Category }}</span></p>
                    <p><strong>Assigned Date:</strong> {{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                    <p><strong>Assigned by:</strong> {{ $complaint->mcmc->M_Name }}</p>
                    <p><strong>Days Since Assignment:</strong> 
                        @php $daysSince = $complaint->C_AssignedDate->diffInDays(now()); @endphp
                        <span class="inline-block px-2 py-0.5 rounded text-sm {{ $daysSince > 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $daysSince }} days
                        </span>
                    </p>
                    <p><strong>Verification Status:</strong> 
                        <span class="inline-block px-2 py-0.5 rounded text-sm bg-{{ $complaint->getVerificationBadgeColor() }}-100 text-{{ $complaint->getVerificationBadgeColor() }}-800">
                            {{ $complaint->C_VerificationStatus ?? 'Pending' }}
                        </span>
                        @if($complaint->C_VerificationDate)
                            <br><small class="text-gray-500">{{ $complaint->C_VerificationDate->format('d M Y, H:i') }}</small>
                        @endif
                    </p>
                    @if($complaint->isRejected() && $complaint->C_RejectionReason)
                        <p><strong>Rejection Reason:</strong>
                            <span class="text-red-600">{{ Str::limit($complaint->C_RejectionReason, 100) }}</span>
                            @if(strlen($complaint->C_RejectionReason) > 100)
                                <br><a href="#" class="text-blue-500 hover:underline" data-toggle="modal" data-target="#rejectionModal">View Full Reason</a>
                            @endif
                        </p>
                    @endif
                </div>
                @if($complaint->C_Comment)
                    <div class="mt-4">
                        <strong>Assignment Comment:</strong>
                        <p class="text-gray-600">{{ $complaint->C_Comment }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Public User Info & Agency Contact -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Public User Info -->
            <div class="border rounded-lg p-4 shadow-sm">
                <h5 class="text-lg font-medium mb-4 text-indigo-600">Submitted by</h5>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Name:</strong> {{ $complaint->inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $complaint->inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $complaint->inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $complaint->inquiry->publicUser->PU_Address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Agency Contact -->
            <div class="border rounded-lg p-4 shadow-sm">
                <h5 class="text-lg font-medium mb-4 text-yellow-600">Agency Contact</h5>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Agency:</strong> {{ $complaint->agency->A_Name }}</p>
                    <p><strong>Email:</strong> {{ $complaint->agency->A_Email }}</p>
                    <p><strong>Phone:</strong> {{ $complaint->agency->A_PhoneNum ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $complaint->agency->A_Address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6">
            @if($userType === 'agency' && $complaint->agency->A_ID === Auth::user()->A_ID)
                @if($complaint->isPendingVerification())
                    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-3">
                        <strong class="block font-medium">Verification Required</strong>
                        <p>Please verify if this inquiry falls under your agency's scope before proceeding.</p>
                    </div>
                    <a href="{{ route('assignments.verify', $complaint->C_ID) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600">
                        <i class="fas fa-clipboard-check mr-2"></i> Verify Assignment Scope
                    </a>
                @elseif($complaint->isAccepted())
                    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-3">
                        <strong class="block font-medium">Assignment Accepted</strong>
                        <p>You have accepted this assignment. You can now proceed with the inquiry review.</p>
                    </div>
                    <a href="{{ route('assignments.review', $complaint->C_ID) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600">
                        <i class="fas fa-edit mr-2"></i> Update Review
                    </a>
                @elseif($complaint->isRejected())
                    <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-3">
                        <strong class="block font-medium">Assignment Rejected</strong>
                        <p>You have rejected this assignment. MCMC will reassign it to another agency.</p>
                    </div>
                @endif
            @endif

            @if($userType === 'mcmc')
                @if($complaint->isRejected())
                    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-3">
                        <strong class="block font-medium">Assignment Rejected</strong>
                        <p>This assignment was rejected by the agency. Please reassign to an appropriate agency.</p>
                    </div>
                @endif
                <a href="{{ route('assignments.reassign', $complaint->C_ID) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600">
                    <i class="fas fa-exchange-alt mr-2"></i> Reassign
                </a>
                @if($complaint->isRejected())
                    <a href="{{ route('assignments.rejected') }}" class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded hover:bg-red-600 ml-2">
                        <i class="fas fa-list mr-2"></i> View All Rejected
                    </a>
                @endif
            @endif

            <a href="{{ route('assignments.history', $complaint->C_ID) }}" class="inline-flex items-center px-4 py-2 bg-blue-400 text-white text-sm font-medium rounded hover:bg-blue-500 ml-2">
                <i class="fas fa-history mr-2"></i> View History
            </a>
            <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded hover:bg-gray-400 ml-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
