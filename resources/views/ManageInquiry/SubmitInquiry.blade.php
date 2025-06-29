@extends('layouts.app')

@section('title', 'Submit New Inquiry - MySebenarnya System')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Submit New Inquiry</h1>
                <p class="text-gray-600">Fill out the form below to submit your inquiry</p>
            </div>
            <a href="{{ route('publicuser.inquiries') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 mt-4 sm:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Back to Inquiries
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>Inquiry Details
                </h3>
            </div>
            <div class="p-6">
                <!-- Display general errors -->
                @if($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('inquiries.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" 
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
                                    <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="6" required
                                  placeholder="Please provide detailed description of your inquiry..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Please provide as much detail as possible to help us understand and process your inquiry effectively.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="source" class="block text-sm font-semibold text-gray-700 mb-2">Source</label>
                            <input type="text" id="source" name="source" value="{{ old('source') }}" 
                                   placeholder="e.g., Website, Phone Call, Email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('source') border-red-500 @enderror">
                            @error('source')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Optional: How did you hear about us or where did this inquiry originate?
                            </p>
                        </div>
                        
                        <div>
                            <label for="attachment" class="block text-sm font-semibold text-gray-700 mb-2">Attachment</label>
                            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('attachment') border-red-500 @enderror">
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Optional: Upload supporting documents (PDF, DOC, DOCX, JPG, PNG - Max 2MB)
                            </p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-blue-800 font-semibold mb-2">Please Note:</h4>
                                <ul class="text-blue-700 space-y-1">
                                    <li>• Your inquiry will be assigned a unique ID once submitted</li>
                                    <li>• You will receive updates on the status of your inquiry</li>
                                    <li>• Processing time may vary depending on the category and complexity</li>
                                    <li>• All fields marked with <span class="text-red-500">*</span> are required</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <a href="{{ route('publicuser.inquiries') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Inquiry
                        </button>
                    </div>
                </form>
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
</script>
@endsection