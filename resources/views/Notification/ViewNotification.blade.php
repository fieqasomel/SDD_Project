@extends('layouts.app')

@section('title', 'Notifications - SDD System')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
            <p class="text-gray-600 mt-1">Stay updated with your inquiry assignments and activities</p>
        </div>
        @if($userType === 'mcmc')
            <div class="flex space-x-3">
                <a href="{{ route('assignments.rejected') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-times-circle mr-2"></i> View Rejected Assignments
                </a>
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-tasks mr-2"></i> Manage Assignments
                </a>
            </div>
        @elseif($userType === 'agency')
            <div class="flex space-x-3">
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-inbox mr-2"></i> View Assignments
                </a>
            </div>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($notifications->isEmpty())
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">No Notifications</h3>
                <p class="text-gray-600">
                    @if($userType === 'mcmc')
                        You don't have any rejection notifications at the moment.
                    @elseif($userType === 'agency')
                        You don't have any assignment notifications at the moment.
                    @else
                        You don't have any notifications at the moment.
                    @endif
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200 {{ $notification->N_Status === 'UNREAD' ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    @if($userType === 'mcmc')
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-times-circle text-red-600"></i>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Assignment Rejected
                                        </span>
                                    @elseif($userType === 'agency')
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-inbox text-blue-600"></i>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New Assignment
                                        </span>
                                    @endif
                                    
                                    @if($notification->N_Status === 'UNREAD')
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-green-500 text-xs mr-1"></i> New
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-900 mb-2">
                                    {{ $notification->N_Message }}
                                </div>
                                
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($notification->N_Timestamp)->format('M d, Y \a\t h:i A') }}
                                    <span class="mx-2">•</span>
                                    {{ \Carbon\Carbon::parse($notification->N_Timestamp)->diffForHumans() }}
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if($userType === 'agency' && isset($notification->complaint_id))
                                    <a href="{{ route('assignments.view', $notification->complaint_id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                        <i class="fas fa-eye mr-1"></i> View Assignment
                                    </a>
                                @elseif($userType === 'mcmc')
                                    <a href="{{ route('assignments.rejected') }}" 
                                       class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                        <i class="fas fa-tasks mr-1"></i> Manage
                                    </a>
                                @endif
                                
                                @if($notification->N_Status === 'UNREAD' && isset($notification->N_ID))
                                    <button onclick="markAsRead('{{ $notification->N_ID }}')" 
                                            class="inline-flex items-center px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded">
                                        <i class="fas fa-check mr-1"></i> Mark Read
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if(method_exists($notifications, 'links'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Information Cards -->
    <div class="grid md:grid-cols-2 gap-6 mt-8">
        @if($userType === 'mcmc')
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Rejection Notifications</h3>
                        <p class="text-red-100 text-sm">Get notified when agencies reject assignments so you can reassign them quickly.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Assignment Management</h3>
                        <p class="text-blue-100 text-sm">Efficiently manage and track all inquiry assignments across agencies.</p>
                    </div>
                </div>
            </div>
        @elseif($userType === 'agency')
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Assignment Notifications</h3>
                        <p class="text-teal-100 text-sm">Receive instant notifications when MCMC assigns new inquiries to your agency.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Quick Actions</h3>
                        <p class="text-green-100 text-sm">Accept or reject assignments quickly to maintain efficient workflow.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection