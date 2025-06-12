@extends('layouts.app')

@section('title', 'Edit Inquiry - ' . $inquiry->I_ID)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Inquiry - {{ $inquiry->I_ID }}</h1>
                <p class="text-gray-600">Update the inquiry details below</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-eye mr-2"></i>View Details
                </a>
                <a href="{{ route('inquiries.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>Edit Inquiry Details
                </h3>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('inquiries.update', $inquiry->I_ID) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $inquiry->I_Title) }}" 
                                   placeholder="Enter inquiry title" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('category') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" 
                                            {{ old('category', $inquiry->I_Category) === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status field for admin users -->
                    @if(isset($statuses) && count($statuses) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('status') border-red-500 @enderror">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" 
                                                {{ old('status', $inquiry->I_Status) === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Status</label>
                                <div class="px-4 py-3 bg-gray-50 rounded-lg">
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
                        </div>
                    @endif

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="6" required
                                  placeholder="Please provide detailed description of your inquiry..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('description') border-red-500 @enderror">{{ old('description', $inquiry->I_Description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="source" class="block text-sm font-semibold text-gray-700 mb-2">Source</label>
                            <input type="text" id="source" name="source" value="{{ old('source', $inquiry->I_Source) }}" 
                                   placeholder="e.g., Website, Phone Call, Email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('source') border-red-500 @enderror">
                            @error('source')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        
                        <div>
                            <label for="attachment" class="block text-sm font-semibold text-gray-700 mb-2">Attachment</label>
                            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('attachment') border-red-500 @enderror">
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Upload new file to replace existing attachment (PDF, DOC, DOCX, JPG, PNG - Max 2MB)
                            </p>
                            
                            @if($inquiry->I_filename && $inquiry->InfoPath)
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Current file:</p>
                                    <a href="{{ Storage::url($inquiry->InfoPath) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fas fa-download mr-2"></i>{{ $inquiry->I_filename }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Information about editing permissions -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-blue-800 font-semibold mb-2">Edit Information:</h4>
                                <ul class="text-blue-700 space-y-1">
                                    <li>• Inquiry ID: <strong>{{ $inquiry->I_ID }}</strong> (cannot be changed)</li>
                                    <li>• Date Submitted: <strong>{{ $inquiry->I_Date ? $inquiry->I_Date->format('F j, Y') : 'N/A' }}</strong> (cannot be changed)</li>
                                    @if(isset($statuses) && count($statuses) > 0)
                                        <li>• You can update the status of this inquiry</li>
                                    @endif
                                    <li>• Changes will be saved immediately upon submission</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Update Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inquiry Summary -->
        <div class="bg-white rounded-2xl shadow-lg mt-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>Inquiry Summary
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Inquiry ID:</span>
                            <span class="text-gray-900">{{ $inquiry->I_ID }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Current Status:</span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $inquiry->I_Status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Date Submitted:</span>
                            <span class="text-gray-900">{{ $inquiry->I_Date ? $inquiry->I_Date->format('F j, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @if($inquiry->publicUser)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="font-semibold text-gray-700">Submitted By:</span>
                                <span class="text-gray-900">{{ $inquiry->publicUser->PU_Name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span class="text-gray-900">{{ $inquiry->publicUser->PU_Email }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Can Edit:</span>
                            <span class="flex items-center">
                                @if($inquiry->canBeEdited())
                                    <i class="fas fa-check text-green-500 mr-1"></i>
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <i class="fas fa-times text-red-500 mr-1"></i>
                                    <span class="text-red-600 font-semibold">No</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Character counter for description
    document.getElementById('description').addEventListener('input', function() {
        const maxLength = 1000;
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        // Create or update character counter
        let counter = document.getElementById('desc-counter');
        if (!counter) {
            counter = document.createElement('p');
            counter.id = 'desc-counter';
            counter.className = 'mt-1 text-sm text-gray-500';
            this.parentNode.appendChild(counter);
        }
        
        counter.textContent = `${currentLength}/${maxLength} characters`;
        
        if (remaining < 50) {
            counter.className = 'mt-1 text-sm text-yellow-600';
        } else if (remaining < 0) {
            counter.className = 'mt-1 text-sm text-red-600';
        } else {
            counter.className = 'mt-1 text-sm text-gray-500';
        }
    });

    // File size validation
    document.getElementById('attachment').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 2MB');
                this.value = '';
            }
        }
    });

    // Status change confirmation for admin users
    @if(isset($statuses) && count($statuses) > 0)
        document.getElementById('status').addEventListener('change', function() {
            const currentStatus = '{{ $inquiry->I_Status }}';
            const newStatus = this.value;
            
            if (currentStatus !== newStatus) {
                const confirmMessage = `Are you sure you want to change the status from "${currentStatus}" to "${newStatus}"?`;
                if (!confirm(confirmMessage)) {
                    this.value = currentStatus;
                }
            }
        });
    @endif
</script>
@endsection