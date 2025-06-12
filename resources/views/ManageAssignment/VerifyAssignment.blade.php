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
                <p><strong>Agency Category:</strong> <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $complaint->agency->A_Category }}</span></p>
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
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600"><strong>Inquiry Title:</strong></p>
                <p class="text-blue-600 font-semibold">{{ $complaint->inquiry->I_Title }}</p>

                <p class="mt-2 text-sm text-gray-600"><strong>Category:</strong></p>
                <p><span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $complaint->inquiry->I_Category }}</span></p>

                <p class="mt-2 text-sm text-gray-600"><strong>Description:</strong></p>
                <div class="bg-gray-100 p-4 rounded">{{ $complaint->inquiry->I_Description }}</div>

                @if($complaint->inquiry->I_filename)
                    <p class="mt-4 text-sm text-gray-600 font-medium">Supporting Evidence:</p>
                    <a href="{{ Storage::url($complaint->inquiry->InfoPath) }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                        <i class="fas fa-paperclip mr-2"></i> {{ $complaint->inquiry->I_filename }}
                    </a>
                @endif
            </div>
            <div>
                <div class="bg-gray-50 p-4 rounded shadow">
                    <p class="text-sm text-gray-700"><strong>Submitted by:</strong> {{ $complaint->inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $complaint->inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $complaint->inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</p>
                    <p><strong>Submission Date:</strong> {{ $complaint->inquiry->I_Date->format('d M Y, H:i') }}</p>
                    <p><strong>Source:</strong> {{ $complaint->inquiry->I_Source ?? 'Online Portal' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Form -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-green-200">
                <h3 class="text-lg font-medium text-green-700 flex items-center"><i class="fas fa-clipboard-check mr-2"></i> Verification Decision</h3>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('assignments.processVerification', $complaint->C_ID) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Verification Decision <span class="text-red-500">*</span></label>
                        <div class="flex gap-6">
                            <label class="flex items-start gap-2">
                                <input type="radio" name="verification_action" value="accept" id="accept" class="mt-1" {{ old('verification_action') === 'accept' ? 'checked' : '' }}>
                                <span><strong>Accept Assignment</strong><br><small class="text-gray-500">Falls under our agency's scope</small></span>
                            </label>
                            <label class="flex items-start gap-2">
                                <input type="radio" name="verification_action" value="reject" id="reject" class="mt-1" {{ old('verification_action') === 'reject' ? 'checked' : '' }}>
                                <span><strong>Reject Assignment</strong><br><small class="text-gray-500">Does not fall under our scope</small></span>
                            </label>
                        </div>
                        @error('verification_action')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 hidden" id="rejection_reason_group">
                        <label for="rejection_reason" class="block font-medium">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="w-full p-2 border rounded @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason') }}</textarea>
                        <p class="text-gray-500 text-sm mt-1">Explain clearly why this inquiry is out of scope</p>
                        @error('rejection_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 hidden" id="verification_comment_group">
                        <label for="verification_comment" class="block font-medium">Additional Comments (Optional)</label>
                        <textarea name="verification_comment" id="verification_comment" rows="3" class="w-full p-2 border rounded">{{ old('verification_comment') }}</textarea>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-check mr-2"></i>Submit Decision
                        </button>
                        <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800">Verification Guidelines</h3>
            </div>
            <div class="px-6 py-4 text-sm text-gray-700">
                <p class="mb-2">Review the inquiry and select:</p>
                <ul class="list-disc list-inside">
                    <li><strong>Accept:</strong> Within your agency scope</li>
                    <li><strong>Reject:</strong> Outside your agency scope</li>
                </ul>
                <div class="mt-4">
                    <p><strong>Your Agency Scope:</strong></p>
                    <p>Category: {{ $complaint->agency->A_Category }}</p>
                    <p>Agency: {{ $complaint->agency->A_Name }}</p>
                </div>
                <div class="mt-4">
                    <p><strong>Important Notes:</strong></p>
                    <ul class="list-disc list-inside text-sm">
                        <li>Accepted cases proceed to verification</li>
                        <li>Rejected cases return to MCMC</li>
                        <li>All actions are logged</li>
                        <li>Provide detailed rejection reasons</li>
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

        const toggleGroups = () => {
            const action = document.querySelector('input[name="verification_action"]:checked')?.value;
            if (action === 'accept') {
                rejectionGroup.classList.add('hidden');
                commentGroup.classList.remove('hidden');
            } else if (action === 'reject') {
                rejectionGroup.classList.remove('hidden');
                commentGroup.classList.add('hidden');
            } else {
                rejectionGroup.classList.add('hidden');
                commentGroup.classList.add('hidden');
            }
        }

        document.querySelectorAll('input[name="verification_action"]').forEach(el => {
            el.addEventListener('change', toggleGroups);
        });

        toggleGroups();
    });
</script>
@endsection
