@extends('layouts.app')

@section('title', 'Assignment History')

@section('content')
<div class="w-full px-6 py-4">
    <div class="w-full">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Assignment History</h3>
                <a href="{{ route('assignments.view', $complaint->C_ID) }}"
                   class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-sm px-3 py-1 rounded-md">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assignment
                </a>
            </div>

            <div class="p-6 space-y-8">
                <!-- Assignment Summary -->
                <div class="border border-blue-300 rounded-lg shadow-sm">
                    <div class="bg-blue-100 px-4 py-2 rounded-t-lg">
                        <h5 class="text-blue-700 font-semibold">Assignment Summary</h5>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Assignment ID:</p>
                                <p class="text-blue-600">{{ $complaint->C_ID }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Inquiry ID:</p>
                                <p class="text-cyan-600">{{ $complaint->inquiry->I_ID }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Current Agency:</p>
                                <p class="text-green-600">{{ $complaint->agency->A_Name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Current Status:</p>
                                <span class="inline-block text-white text-xs font-medium px-2 py-1 rounded-full bg-{{ $complaint->inquiry->getStatusBadgeColor() }}">
                                    {{ $complaint->inquiry->I_Status }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Inquiry Title:</p>
                                <p>{{ $complaint->inquiry->I_Title }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Assignment Date:</p>
                                <p>{{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Days Active:</p>
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-2 py-1 rounded">
                                    {{ $complaint->C_AssignedDate->diffInDays(now()) }} days
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Timeline -->
                <div class="border border-gray-300 rounded-lg shadow-sm">
                    <div class="bg-gray-100 px-4 py-2 rounded-t-lg">
                        <h5 class="text-gray-700 font-semibold"><i class="fas fa-history mr-1"></i> Assignment History Timeline</h5>
                    </div>
                    <div class="p-4">
                        @if(empty($history))
                            <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded">
                                <p><i class="fas fa-info-circle mr-2"></i>No history records found for this assignment.</p>
                            </div>
                        @else
                            <div class="relative border-l-2 border-gray-300 pl-6">
                                @foreach($history as $index => $entry)
                                    @php
                                        preg_match('/\[(.*?)\]\s+(.*?):\s+(.*)/', $entry, $matches);
                                        $timestamp = $matches[1] ?? '';
                                        $userInfo = $matches[2] ?? '';
                                        $action = $matches[3] ?? $entry;

                                        $icon = 'fas fa-circle';
                                        $color = 'blue-500';

                                        if (strpos($action, 'assigned') !== false) {
                                            $icon = 'fas fa-user-plus';
                                            $color = 'green-500';
                                        } elseif (strpos($action, 'reassigned') !== false) {
                                            $icon = 'fas fa-exchange-alt';
                                            $color = 'yellow-500';
                                        } elseif (strpos($action, 'Status updated') !== false) {
                                            $icon = 'fas fa-edit';
                                            $color = 'blue-400';
                                        } elseif (strpos($action, 'Resolved') !== false) {
                                            $icon = 'fas fa-check-circle';
                                            $color = 'green-500';
                                        } elseif (strpos($action, 'Closed') !== false) {
                                            $icon = 'fas fa-times-circle';
                                            $color = 'gray-500';
                                        }
                                    @endphp

                                    <div class="mb-8 relative">
                                        <div class="absolute -left-3 top-0">
                                            <div class="w-6 h-6 rounded-full bg-{{ $color }} flex items-center justify-center text-white text-xs">
                                                <i class="{{ $icon }}"></i>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded shadow ml-4">
                                            <h6 class="text-sm font-semibold text-{{ $color }}">{{ $action }}</h6>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>{{ $timestamp ? \Carbon\Carbon::parse($timestamp)->format('d M Y, H:i') : 'Unknown time' }}
                                            </p>
                                            @if($userInfo)
                                                <p class="text-xs text-gray-600 mt-1">
                                                    <i class="fas fa-user mr-1"></i>{{ $userInfo }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Inquiry Details -->
                    <div class="border border-blue-200 rounded-lg shadow-sm">
                        <div class="bg-blue-100 px-4 py-2 rounded-t-lg">
                            <h5 class="text-blue-700 font-semibold">Inquiry Details</h5>
                        </div>
                        <div class="p-4 bg-white text-sm text-gray-700 space-y-2">
                            <p><strong>Category:</strong> <span class="inline-block bg-blue-500 text-white px-2 py-1 rounded text-xs">{{ $complaint->inquiry->I_Category }}</span></p>
                            <p><strong>Submitted by:</strong> {{ $complaint->inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                            <p><strong>Submission Date:</strong> {{ $complaint->inquiry->I_Date ? \Carbon\Carbon::parse($complaint->inquiry->I_Date)->format('d M Y') : 'N/A' }}</p>
                            <p><strong>Source:</strong> {{ $complaint->inquiry->I_Source ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Current Assignment -->
                    <div class="border border-green-200 rounded-lg shadow-sm">
                        <div class="bg-green-100 px-4 py-2 rounded-t-lg">
                            <h5 class="text-green-700 font-semibold">Current Assignment</h5>
                        </div>
                        <div class="p-4 bg-white text-sm text-gray-700 space-y-2">
                            <p><strong>Agency:</strong> {{ $complaint->agency->A_Name }}</p>
                            <p><strong>Agency Category:</strong> <span class="inline-block bg-green-500 text-white px-2 py-1 rounded text-xs">{{ $complaint->agency->A_Category }}</span></p>
                            <p><strong>Assigned by:</strong> {{ $complaint->mcmc->M_Name }}</p>
                            <p><strong>Assignment Duration:</strong> {{ $complaint->C_AssignedDate->diffInDays(now()) }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i> View Assignment Details
                    </a>

                    @if($userType === 'mcmc')
                        <a href="{{ route('assignments.reassign', $complaint->C_ID) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            <i class="fas fa-exchange-alt mr-1"></i> Reassign
                        </a>
                    @endif

                    @if($userType === 'agency' && $complaint->agency->A_ID === Auth::user()->A_ID)
                        <a href="{{ route('assignments.review', $complaint->C_ID) }}" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                            <i class="fas fa-edit mr-1"></i> Update Review
                        </a>
                    @endif

                    <a href="{{ route('assignments.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        <i class="fas fa-list mr-1"></i> Back to Assignments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
