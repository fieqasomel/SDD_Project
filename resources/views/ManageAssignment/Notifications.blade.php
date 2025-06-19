@extends('layouts.app')

@section('title', 'Notifications - Assignment Rejections')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Assignment Notifications</h3>
                <p class="text-sm text-gray-600 mt-1">Notifications about rejected assignments and other important updates</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assignments
                </a>
                <a href="{{ route('assignments.rejected') }}" class="inline-flex items-center px-3 py-1 bg-red-400 text-white text-sm rounded hover:bg-red-500">
                    <i class="fas fa-list mr-1"></i> View All Rejected
                </a>
            </div>
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="border rounded-lg p-4 {{ $notification->isUnread() ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($notification->isUnread())
                                        <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                        <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded">NEW</span>
                                    @else
                                        <span class="inline-block w-2 h-2 bg-gray-400 rounded-full"></span>
                                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">READ</span>
                                    @endif
                                    <span class="text-sm text-gray-500">{{ $notification->N_Timestamp->format('d M Y, H:i') }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="font-medium text-gray-800">Assignment Rejection</span>
                                </div>
                                
                                <p class="text-gray-700 leading-relaxed">{{ $notification->N_Message }}</p>
                            </div>
                            
                            <div class="ml-4 flex flex-col gap-2">
                                @if($notification->isUnread())
                                    <a href="{{ route('assignments.markNotificationRead', $notification->N_ID) }}" 
                                       class="inline-flex items-center px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                        <i class="fas fa-check mr-1"></i> Mark Read
                                    </a>
                                @endif
                                
                                <!-- Extract assignment ID from message and provide quick action -->
                                @php
                                    preg_match('/Assignment #(CMP\d+)/', $notification->N_Message, $matches);
                                    $assignmentId = $matches[1] ?? null;
                                @endphp
                                
                                @if($assignmentId)
                                    <a href="{{ route('assignments.view', $assignmentId) }}" 
                                       class="inline-flex items-center px-3 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('assignments.reassign', $assignmentId) }}" 
                                       class="inline-flex items-center px-3 py-1 text-xs bg-yellow-600 text-white rounded hover:bg-yellow-700">
                                        <i class="fas fa-exchange-alt mr-1"></i> Reassign
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Notifications</h4>
                <p class="text-gray-600 mb-4">You don't have any notifications at the moment.</p>
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh notifications every 30 seconds
    setInterval(() => {
        // Check for new notifications via AJAX
        fetch('{{ route("assignments.notifications") }}?ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.newNotifications > 0) {
                    // Show a small notification indicator
                    const indicator = document.createElement('div');
                    indicator.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow-lg z-50';
                    indicator.innerHTML = `<i class="fas fa-bell mr-2"></i>${data.newNotifications} new notification(s)`;
                    document.body.appendChild(indicator);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        indicator.remove();
                    }, 5000);
                }
            })
            .catch(error => console.log('Notification check failed:', error));
    }, 30000);
</script>
@endsection