@extends('layouts.app')

@section('title', 'Filter Inquiry - MCMC Staff')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Filter & Validate Inquiry</h1>
                <p class="text-gray-600">Review inquiry details and determine if it's genuine or non-serious</p>
            </div>
            <a href="{{ route('inquiries.mcmc.new') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to New Inquiries
            </a>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Inquiry Details Card -->
        <div class="bg-white rounded-2xl shadow-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>Inquiry Details
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inquiry ID</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono">
                                {{ $inquiry->I_ID }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ $inquiry->I_Title }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $inquiry->I_Category }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>{{ $inquiry->I_Status }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Submitted</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ \Carbon\Carbon::parse($inquiry->I_Date)->format('d M Y, H:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Name</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ $inquiry->publicUser->PU_Name ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Email</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ $inquiry->publicUser->PU_Email ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Phone</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ $inquiry->publicUser->PU_Phone ?? 'N/A' }}
                            </div>
                        </div>

                        @if($inquiry->I_Source)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                {{ $inquiry->I_Source }}
                            </div>
                        </div>
                        @endif

                        @if($inquiry->I_filename)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supporting Evidence</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                                <a href="{{ Storage::url($inquiry->I_filename) }}" 
                                   target="_blank"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-paperclip mr-1"></i>
                                    View Attachment
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm leading-relaxed">
                        {{ $inquiry->I_Description }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Decision Form -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-gavel mr-2 text-green-600"></i>MCMC Decision
                </h3>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('inquiries.mcmc.process', $inquiry->I_ID) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Action Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Decision</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="approve" 
                                       name="action" 
                                       type="radio" 
                                       value="approve" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                       {{ old('action') == 'approve' ? 'checked' : '' }}>
                                <label for="approve" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="text-green-600 font-semibold">Approve</span> - This is a genuine inquiry and should be forwarded to relevant agency
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="reject" 
                                       name="action" 
                                       type="radio" 
                                       value="reject" 
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                       {{ old('action') == 'reject' ? 'checked' : '' }}>
                                <label for="reject" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="text-red-600 font-semibold">Reject</span> - This is a non-serious submission and should be discarded
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Rejection Reason (shown only when reject is selected) -->
                    <div id="rejection-reason-section" class="hidden">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <select id="rejection_reason" 
                                name="rejection_reason"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Select rejection reason...</option>
                            <option value="Spam or irrelevant content" {{ old('rejection_reason') == 'Spam or irrelevant content' ? 'selected' : '' }}>Spam or irrelevant content</option>
                            <option value="Duplicate inquiry" {{ old('rejection_reason') == 'Duplicate inquiry' ? 'selected' : '' }}>Duplicate inquiry</option>
                            <option value="Insufficient information" {{ old('rejection_reason') == 'Insufficient information' ? 'selected' : '' }}>Insufficient information</option>
                            <option value="Outside MCMC jurisdiction" {{ old('rejection_reason') == 'Outside MCMC jurisdiction' ? 'selected' : '' }}>Outside MCMC jurisdiction</option>
                            <option value="Inappropriate language or content" {{ old('rejection_reason') == 'Inappropriate language or content' ? 'selected' : '' }}>Inappropriate language or content</option>
                            <option value="Test submission" {{ old('rejection_reason') == 'Test submission' ? 'selected' : '' }}>Test submission</option>
                            <option value="Other" {{ old('rejection_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- MCMC Notes -->
                    <div>
                        <label for="mcmc_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            MCMC Notes (Optional)
                        </label>
                        <textarea id="mcmc_notes" 
                                  name="mcmc_notes" 
                                  rows="4" 
                                  placeholder="Add any additional notes about this inquiry..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('mcmc_notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">These notes will be recorded for audit purposes.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('inquiries.mcmc.new') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-check mr-2"></i>Process Decision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveRadio = document.getElementById('approve');
    const rejectRadio = document.getElementById('reject');
    const rejectionReasonSection = document.getElementById('rejection-reason-section');
    const rejectionReasonSelect = document.getElementById('rejection_reason');

    function toggleRejectionReason() {
        if (rejectRadio.checked) {
            rejectionReasonSection.classList.remove('hidden');
            rejectionReasonSelect.required = true;
        } else {
            rejectionReasonSection.classList.add('hidden');
            rejectionReasonSelect.required = false;
            rejectionReasonSelect.value = '';
        }
    }

    approveRadio.addEventListener('change', toggleRejectionReason);
    rejectRadio.addEventListener('change', toggleRejectionReason);

    // Initialize on page load
    toggleRejectionReason();
});
</script>
@endsection