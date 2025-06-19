@extends('layouts.app')

@section('title', 'Verify Assignment')

@section('content')
<div class="px-6 py-4 max-w-7xl mx-auto">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Verify Assignment - Scope Review</h2>
            <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to Assignment
            </a>
        </div>
    </div>

    <!-- Assignment Information -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Assignment Information</h3>
        </div>
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <p><strong>Assignment ID:</strong> {{ $complaint->C_ID }}</p>
                <p><strong>Inquiry ID:</strong> {{ $complaint->inquiry->I_ID }}</p>
                <p><strong>Assigned Date:</strong> {{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                <p><strong>Assigned by:</strong> {{ $complaint->mcmc->M_Name }}</p>
                <p><strong>Your Agency:</strong> {{ $complaint->agency->A_Name }}</p>
                <p><strong>Agency Category:</strong> <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ is_array($complaint->agency->A_Category) ? implode(', ', $complaint->agency->A_Category) : $complaint->agency->A_Category }}</span></p>
            </div>
            <div>
                <p><strong>Current Status:</strong> <span class="inline-block px-2 py-1 bg-{{ $complaint->getVerificationBadgeColor() }}-100 text-{{ $complaint->getVerificationBadgeColor() }}-800 rounded">{{ $complaint->C_VerificationStatus }} Verification</span></p>
                <p><strong>Days Since Assignment:</strong> <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $complaint->C_AssignedDate->diffInDays(now()) }} days</span></p>
                <p><strong>Inquiry Status:</strong> <span class="inline-block px-2 py-1 bg-{{ $complaint->inquiry->getStatusBadgeColor() }}-100 text-{{ $complaint->inquiry->getStatusBadgeColor() }}-800 rounded">{{ $complaint->inquiry->I_Status }}</span></p>
            </div>
        </div>
    </div>

    <!-- Inquiry Details -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-yellow-300">
            <h3 class="text-lg font-medium text-yellow-700 flex items-center"><i class="fas fa-search mr-2"></i> Inquiry Details for Scope Review</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Inquiry Information -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Inquiry Title:</p>
                        <p class="text-blue-600 font-semibold text-lg">{{ $complaint->inquiry->I_Title }}</p>
                    </div>

                    <div class="flex gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Category:</p>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded font-medium">{{ $complaint->inquiry->I_Category }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Status:</p>
                            <span class="inline-block px-3 py-1 bg-{{ $complaint->inquiry->getStatusBadgeColor() }}-100 text-{{ $complaint->inquiry->getStatusBadgeColor() }}-800 rounded font-medium">{{ $complaint->inquiry->I_Status }}</span>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-medium">Source:</p>
                        <p class="text-gray-700">{{ $complaint->inquiry->I_Source ?? 'Online Portal' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-medium">Submission Date:</p>
                        <p class="text-gray-700">{{ $complaint->inquiry->I_Date ? \Carbon\Carbon::parse($complaint->inquiry->I_Date)->format('d M Y, H:i') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Assignment Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-800 mb-3">Assignment Details</h4>
                    <div class="space-y-2 text-sm">
                        <p><strong>Assignment ID:</strong> {{ $complaint->C_ID }}</p>
                        <p><strong>Assigned by:</strong> {{ $complaint->mcmc->M_Name }}</p>
                        <p><strong>Assigned Date:</strong> {{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                        <p><strong>Days Pending:</strong> 
                            <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">
                                {{ $complaint->C_AssignedDate->diffInDays(now()) }} days
                            </span>
                        </p>
                        @if($complaint->C_Comment)
                            <div class="mt-3">
                                <p><strong>Assignment Notes:</strong></p>
                                <p class="text-gray-600 italic">{{ $complaint->C_Comment }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Full Description -->
            <div class="mb-6">
                <p class="text-sm text-gray-600 font-medium mb-2">Description:</p>
                <div class="bg-gray-100 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-gray-800 leading-relaxed">{{ $complaint->inquiry->I_Description }}</p>
                </div>
            </div>

            <!-- Supporting Evidence -->
            @if($complaint->inquiry->I_filename)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 font-medium mb-2">Supporting Evidence:</p>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <a href="{{ Storage::url($complaint->inquiry->InfoPath) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i> Download: {{ $complaint->inquiry->I_filename }}
                        </a>
                        <p class="text-xs text-blue-600 mt-2">Click to view or download the attached file</p>
                    </div>
                </div>
            @endif

            <!-- Quick Agency Scope Reference -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="font-medium text-green-800 mb-2 flex items-center">
                    <i class="fas fa-building mr-2"></i> Your Agency Scope Reference
                </h4>
                <div class="text-sm text-green-700">
                    <p><strong>Agency:</strong> {{ $complaint->agency->A_Name }}</p>
                    <p><strong>Categories:</strong> {{ is_array($complaint->agency->A_Category) ? implode(', ', $complaint->agency->A_Category) : $complaint->agency->A_Category }}</p>
                    <p class="mt-2 text-xs">Review if this inquiry falls within your agency's scope and categories.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Form -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-green-200">
                <h3 class="text-lg font-medium text-green-700 flex items-center"><i class="fas fa-clipboard-check mr-2"></i> Make Your Decision</h3>
                <p class="text-sm text-gray-600 mt-1">Review the inquiry details above and decide if it falls under your agency's scope</p>
            </div>
            <div class="px-6 py-6">
                <form action="{{ route('assignments.processVerification', $complaint->C_ID) }}" method="POST" id="verificationForm">
                    @csrf
                    
                    <!-- Decision Section -->
                    <div class="mb-6">
                        <label class="block font-semibold mb-4 text-gray-800">Your Decision <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Accept Option -->
                            <label class="cursor-pointer">
                                <input type="radio" name="verification_action" value="accept" id="accept" class="sr-only" {{ old('verification_action') === 'accept' ? 'checked' : '' }}>
                                <div class="border-2 border-green-200 rounded-lg p-4 hover:border-green-400 transition-colors accept-option">
                                    <div class="flex items-center mb-2">
                                        <div class="w-4 h-4 border-2 border-green-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full hidden accept-radio"></div>
                                        </div>
                                        <span class="font-semibold text-green-700 text-lg">✓ APPROVE & ACCEPT</span>
                                    </div>
                                    <p class="text-sm text-gray-600 ml-7">This inquiry falls within our agency's scope and we will handle it</p>
                                    <div class="mt-2 ml-7">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Will proceed to case handling</span>
                                    </div>
                                </div>
                            </label>

                            <!-- Reject Option -->
                            <label class="cursor-pointer">
                                <input type="radio" name="verification_action" value="reject" id="reject" class="sr-only" {{ old('verification_action') === 'reject' ? 'checked' : '' }}>
                                <div class="border-2 border-red-200 rounded-lg p-4 hover:border-red-400 transition-colors reject-option">
                                    <div class="flex items-center mb-2">
                                        <div class="w-4 h-4 border-2 border-red-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-red-500 rounded-full hidden reject-radio"></div>
                                        </div>
                                        <span class="font-semibold text-red-700 text-lg">✗ REJECT</span>
                                    </div>
                                    <p class="text-sm text-gray-600 ml-7">This inquiry does not fall under our agency's scope</p>
                                    <div class="mt-2 ml-7">
                                        <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Will notify MCMC for reassignment</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('verification_action')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rejection Reason (Hidden by default) -->
                    <div class="mb-6 hidden" id="rejection_reason_group">
                        <label for="rejection_reason" class="block font-semibold mb-2 text-red-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                                  class="w-full p-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('rejection_reason') border-red-500 @enderror" 
                                  placeholder="Please explain in detail why this inquiry does not fall under your agency's scope...">{{ old('rejection_reason') }}</textarea>
                        <div class="mt-2 text-sm text-red-600">
                            <p><strong>Important:</strong> MCMC will use this reason to reassign to the appropriate agency</p>
                            <p>• Be specific about why it's out of scope</p>
                            <p>• Suggest which agency might be more appropriate if known</p>
                        </div>
                        @error('rejection_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Verification Comments (Hidden by default) -->
                    <div class="mb-6 hidden" id="verification_comment_group">
                        <label for="verification_comment" class="block font-medium mb-2 text-green-700">
                            <i class="fas fa-comment mr-1"></i>Additional Comments (Optional)
                        </label>
                        <textarea name="verification_comment" id="verification_comment" rows="3" 
                                  class="w-full p-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                  placeholder="Any additional notes about accepting this inquiry...">{{ old('verification_comment') }}</textarea>
                        <p class="text-sm text-green-600 mt-1">Optional notes about your decision or any special considerations</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4 pt-4 border-t">
                        <button type="submit" class="flex-1 md:flex-none px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Decision
                        </button>
                        <a href="{{ route('assignments.view', $complaint->C_ID) }}" 
                           class="flex-1 md:flex-none px-8 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Assignment
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guidelines Sidebar -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>Guidelines
                </h3>
            </div>
            <div class="px-4 py-4 text-sm text-gray-700 space-y-4">
                <!-- Decision Guide -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Decision Guide:</h4>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <span class="text-green-500 font-bold">✓</span>
                            <div>
                                <strong class="text-green-700">Accept</strong>
                                <p class="text-xs">Within your agency scope</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-red-500 font-bold">✗</span>
                            <div>
                                <strong class="text-red-700">Reject</strong>
                                <p class="text-xs">Outside your agency scope</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agency Scope -->
                <div class="bg-blue-50 p-3 rounded">
                    <h4 class="font-semibold text-blue-800 mb-2">Your Agency:</h4>
                    <p class="text-blue-700 font-medium">{{ $complaint->agency->A_Name }}</p>
                    <p class="text-xs text-blue-600 mt-1">
                        <strong>Categories:</strong><br>
                        {{ is_array($complaint->agency->A_Category) ? implode(', ', $complaint->agency->A_Category) : $complaint->agency->A_Category }}
                    </p>
                </div>

                <!-- Process Flow -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">What happens next:</h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-start gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full mt-1"></span>
                            <span><strong>Accept:</strong> Inquiry moves to your caseload</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full mt-1"></span>
                            <span><strong>Reject:</strong> MCMC gets notified for reassignment</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-1"></span>
                            <span>All decisions are logged and tracked</span>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 p-3 rounded">
                    <h4 class="font-semibold text-yellow-800 mb-2">⚠ Important:</h4>
                    <ul class="list-disc list-inside text-xs text-yellow-700 space-y-1">
                        <li>Review all details carefully</li>
                        <li>Provide clear rejection reasons</li>
                        <li>Decision affects case processing</li>
                        <li>MCMC will be automatically notified</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rejectionGroup = document.getElementById('rejection_reason_group');
        const commentGroup = document.getElementById('verification_comment_group');
        const acceptOption = document.querySelector('.accept-option');
        const rejectOption = document.querySelector('.reject-option');
        const acceptRadio = document.querySelector('.accept-radio');
        const rejectRadio = document.querySelector('.reject-radio');
        const acceptInput = document.getElementById('accept');
        const rejectInput = document.getElementById('reject');

        const updateUI = () => {
            const action = document.querySelector('input[name="verification_action"]:checked')?.value;
            
            // Reset all states
            acceptOption.classList.remove('border-green-400', 'bg-green-50');
            rejectOption.classList.remove('border-red-400', 'bg-red-50');
            acceptRadio.classList.add('hidden');
            rejectRadio.classList.add('hidden');
            
            if (action === 'accept') {
                acceptOption.classList.add('border-green-400', 'bg-green-50');
                acceptRadio.classList.remove('hidden');
                rejectionGroup.classList.add('hidden');
                commentGroup.classList.remove('hidden');
            } else if (action === 'reject') {
                rejectOption.classList.add('border-red-400', 'bg-red-50');
                rejectRadio.classList.remove('hidden');
                rejectionGroup.classList.remove('hidden');
                commentGroup.classList.add('hidden');
            } else {
                rejectionGroup.classList.add('hidden');
                commentGroup.classList.add('hidden');
            }
        };

        // Handle clicking on the option cards
        acceptOption.addEventListener('click', () => {
            acceptInput.checked = true;
            updateUI();
        });

        rejectOption.addEventListener('click', () => {
            rejectInput.checked = true;
            updateUI();
        });

        // Handle radio button changes
        document.querySelectorAll('input[name="verification_action"]').forEach(el => {
            el.addEventListener('change', updateUI);
        });

        // Form validation
        document.getElementById('verificationForm').addEventListener('submit', (e) => {
            const action = document.querySelector('input[name="verification_action"]:checked')?.value;
            const rejectionReason = document.getElementById('rejection_reason').value.trim();
            
            if (!action) {
                e.preventDefault();
                alert('Please select either Accept or Reject before submitting.');
                return;
            }
            
            if (action === 'reject' && !rejectionReason) {
                e.preventDefault();
                alert('Please provide a reason for rejection.');
                document.getElementById('rejection_reason').focus();
                return;
            }
            
            // Confirmation dialog
            const message = action === 'accept' 
                ? 'Are you sure you want to ACCEPT this assignment? You will be responsible for handling this inquiry.'
                : 'Are you sure you want to REJECT this assignment? MCMC will be notified for reassignment.';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });

        // Initialize UI
        updateUI();
    });
</script>
@endsection
