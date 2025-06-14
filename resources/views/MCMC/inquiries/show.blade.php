@extends('layouts.app')

@section('title', 'Inquiry Details - MCMC')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Inquiry Details</h1>
            <a href="{{ route('mcmc.inquiries.new') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        <!-- Inquiry Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Inquiry Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Inquiry ID:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->I_ID }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Title:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->I_Title }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Category:</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $inquiry->I_Category }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status:</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $inquiry->I_Status }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Submission Date:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->I_Date ? $inquiry->I_Date->format('M d, Y') : 'No date' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Source:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->I_Source ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Name:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->publicUser?->PU_Name ?? 'Unknown User' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->publicUser?->PU_Email ?? 'No email' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->publicUser?->PU_PhoneNum ?? 'No phone' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">IC:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->publicUser?->PU_IC ?? 'No IC' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address:</label>
                        <p class="text-sm text-gray-900">{{ $inquiry->publicUser?->PU_Address ?? 'No address' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-900 whitespace-pre-wrap">{{ $inquiry->I_Description }}</p>
            </div>
        </div>

        <!-- Attachments -->
        @if($inquiry->I_filename)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $inquiry->I_filename }}</p>
                            <a href="{{ Storage::url($inquiry->InfoPath) }}" target="_blank" 
                               class="text-sm text-indigo-600 hover:text-indigo-900">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Processing Information (if already processed) -->
        @if($inquiry->processed_by)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Processing Information</h3>
                <div class="bg-blue-50 p-6 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Processed By:</label>
                            <p class="text-sm text-gray-900">{{ $inquiry->processor?->M_Name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Processing Date:</label>
                            <p class="text-sm text-gray-900">{{ $inquiry->processed_date ? $inquiry->processed_date->format('M d, Y H:i') : 'Not processed' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Priority Level:</label>
                            <p class="text-sm text-gray-900">{{ $inquiry->priority_level ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Serious Inquiry:</label>
                            <p class="text-sm text-gray-900">{{ $inquiry->is_serious ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                    @if($inquiry->mcmc_notes)
                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-500">MCMC Notes:</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $inquiry->mcmc_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Validation Form (if not processed yet) -->
        @if(!$inquiry->processed_by)
            <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Process Inquiry</h3>
                <form method="POST" action="{{ route('mcmc.inquiries.validate', $inquiry->I_ID) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Decision</label>
                            <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Decision</option>
                                <option value="Validated">Validate Inquiry</option>
                                <option value="Rejected">Reject Inquiry</option>
                                <option value="Non-Serious">Mark as Non-Serious</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priority_level" class="block text-sm font-medium text-gray-700">Priority Level</label>
                            <select name="priority_level" id="priority_level" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Priority</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                            @error('priority_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_serious" class="block text-sm font-medium text-gray-700">Is Serious Inquiry?</label>
                            <select name="is_serious" id="is_serious" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select</option>
                                <option value="1">Yes - Serious inquiry</option>
                                <option value="0">No - Non-serious inquiry</option>
                            </select>
                            @error('is_serious')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="mcmc_notes" class="block text-sm font-medium text-gray-700">MCMC Notes</label>
                        <textarea name="mcmc_notes" id="mcmc_notes" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your notes about this inquiry..." required>{{ old('mcmc_notes') }}</textarea>
                        @error('mcmc_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('mcmc.inquiries.new') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Process Inquiry
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-set serious inquiry based on status selection
    document.getElementById('status').addEventListener('change', function() {
        const isSeriousSelect = document.getElementById('is_serious');
        if (this.value === 'Non-Serious') {
            isSeriousSelect.value = '0';
        } else if (this.value === 'Validated') {
            isSeriousSelect.value = '1';
        }
    });
</script>
@endsection