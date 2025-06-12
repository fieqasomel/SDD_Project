@extends('layouts.app')

@section('title', 'Inquiry Details - ' . $inquiry->I_ID)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Inquiry Details - {{ $inquiry->I_ID }}</h1>
                <p class="text-gray-600">View complete inquiry information and status</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                @if($inquiry->canBeEdited())
                    <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('inquiries.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Inquiry Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Inquiry Information -->
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Inquiry Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Inquiry ID</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $inquiry->I_ID }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Title</span>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-900 font-medium">{{ $inquiry->I_Title }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Category</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $inquiry->I_Category }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Status</span>
                            </div>
                            <div class="md:col-span-2">
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Date Submitted</span>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-900">{{ $inquiry->I_Date ? $inquiry->I_Date->format('F j, Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        @if($inquiry->I_Source)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Source</span>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-gray-900">{{ $inquiry->I_Source }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Description</span>
                            </div>
                            <div class="md:col-span-2">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-line">{{ $inquiry->I_Description }}</p>
                                </div>
                            </div>
                        </div>

                        @if($inquiry->I_filename && $inquiry->InfoPath)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Attachment</span>
                                </div>
                                <div class="md:col-span-2">
                                    <a href="{{ Storage::url($inquiry->InfoPath) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                        <i class="fas fa-download mr-2"></i>{{ $inquiry->I_filename }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Tracking -->
                @if(isset($inquiry->progress) && $inquiry->progress->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-history mr-2 text-blue-600"></i>Progress History
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                @foreach($inquiry->progress->sortByDesc('created_at') as $progress)
                                    <div class="relative pl-8">
                                        <div class="absolute left-0 top-2 w-3 h-3 bg-blue-600 rounded-full"></div>
                                        @if(!$loop->last)
                                            <div class="absolute left-1.5 top-5 w-0.5 h-full bg-gray-200"></div>
                                        @endif
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $progress->title }}</h4>
                                            <p class="text-gray-700 mb-2">{{ $progress->description }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $progress->created_at->format('F j, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Submitter Information -->
                @if(isset($userType) && $userType !== 'public' && $inquiry->publicUser)
                    <div class="bg-white rounded-2xl shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Submitted By
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                @if($inquiry->publicUser->PU_ProfilePicture)
                                    <img src="{{ Storage::url($inquiry->publicUser->PU_ProfilePicture) }}" 
                                         alt="Profile Picture" class="w-20 h-20 rounded-full mx-auto object-cover">
                                @else
                                    <div class="w-20 h-20 bg-blue-600 rounded-full mx-auto flex items-center justify-center">
                                        <i class="fas fa-user text-2xl text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">Name:</span>
                                    <span class="text-gray-900">{{ $inquiry->publicUser->PU_Name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">Email:</span>
                                    <span class="text-gray-900">{{ $inquiry->publicUser->PU_Email }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">Phone:</span>
                                    <span class="text-gray-900">{{ $inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-semibold text-gray-700">IC:</span>
                                    <span class="text-gray-900">{{ $inquiry->publicUser->PU_IC ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-bolt mr-2 text-blue-600"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($inquiry->canBeEdited())
                            <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-edit mr-2"></i>Edit Inquiry
                            </a>
                        @endif
                        
                        @if(isset($userType) && $userType === 'public')
                            <button onclick="printInquiry()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-print mr-2"></i>Print Details
                            </button>
                        @endif
                        
                        @if(isset($userType) && $userType !== 'public')
                            <button onclick="exportInquiry()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-file-export mr-2"></i>Export to PDF
                            </button>
                        @endif
                        
                        @if($inquiry->canBeDeleted() && (isset($userType) && ($userType === 'public' || $userType === 'mcmc')))
                            <form method="POST" action="{{ route('inquiries.destroy', $inquiry->I_ID) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <i class="fas fa-trash mr-2"></i>Delete Inquiry
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Status Information -->
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Status Information
                        </h3>
                    </div>
                    <div class="p-6">
                        @switch($inquiry->I_Status)
                            @case('Pending')
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-clock text-yellow-500 mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-yellow-800 font-semibold mb-1">Pending Review</h4>
                                            <p class="text-yellow-700">Your inquiry is waiting to be reviewed by our team.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('In Progress')
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-spinner text-blue-500 mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-blue-800 font-semibold mb-1">In Progress</h4>
                                            <p class="text-blue-700">Your inquiry is currently being processed.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('Resolved')
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-green-800 font-semibold mb-1">Resolved</h4>
                                            <p class="text-green-700">Your inquiry has been resolved successfully.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('Closed')
                                <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-times-circle text-gray-500 mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-gray-800 font-semibold mb-1">Closed</h4>
                                            <p class="text-gray-700">This inquiry has been closed.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('Rejected')
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-red-800 font-semibold mb-1">Rejected</h4>
                                            <p class="text-red-700">This inquiry has been rejected.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-4 text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<script>
    function printInquiry() {
        window.print();
    }
    
    function exportInquiry() {
        // This would typically integrate with a PDF generation service
        alert('PDF export functionality would be implemented here');
    }
</script>
@endsection