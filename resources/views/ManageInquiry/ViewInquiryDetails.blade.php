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
                @if($inquiry->I_Status === 'Pending')
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

        <!-- Action Buttons -->
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
            
            <a href="{{ route('inquiries.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to My Inquiries
            </a>
        </div>
    </div>
</div>
@endsection