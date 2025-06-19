@extends('layouts.app')

@section('title', 'Review Inquiry')

@section('content')
<div class="px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-700">Review Assigned Inquiry</h3>
                <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="inline-flex items-center px-3 py-1 text-sm text-white bg-gray-500 hover:bg-gray-600 rounded">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Assignment
                </a>
            </div>
            <div class="px-6 py-4">

                <!-- Inquiry Details -->
                <div class="mb-6 bg-gray-50 p-4 rounded border border-gray-200">
                    <h5 class="text-lg font-medium mb-3 text-gray-800">Inquiry Information</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p><strong>Inquiry ID:</strong> {{ $complaint->inquiry->I_ID }}</p>
                            <p><strong>Title:</strong> {{ $complaint->inquiry->I_Title }}</p>
                            <p><strong>Category:</strong> 
                                <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                    {{ $complaint->inquiry->I_Category }}
                                </span>
                            </p>
                            <p><strong>Current Status:</strong> 
                                <span class="inline-block text-white text-xs px-2 py-1 rounded 
                                    {{ $complaint->inquiry->getStatusBadgeColor() == 'success' ? 'bg-green-500' : ($complaint->inquiry->getStatusBadgeColor() == 'danger' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                    {{ $complaint->inquiry->I_Status }}
                                </span>
                            </p>
                            <p><strong>Submitted Date:</strong> {{ $complaint->inquiry->I_Date ? \Carbon\Carbon::parse($complaint->inquiry->I_Date)->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>Submitted by:</strong> {{ $complaint->inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $complaint->inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $complaint->inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</p>
                            <p><strong>Assignment Date:</strong> {{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                            <p><strong>Days Since Assignment:</strong> 
                                <span class="inline-block bg-blue-200 text-blue-800 px-2 py-1 rounded">
                                    {{ $complaint->C_AssignedDate->diffInDays(now()) }} days
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <strong>Description:</strong>
                        <p class="text-gray-600 mt-1">{{ $complaint->inquiry->I_Description }}</p>
                    </div>
                    @if($complaint->inquiry->I_filename)
                        <div class="mt-4">
                            <strong>Attachment:</strong><br>
                            <a href="{{ Storage::url($complaint->inquiry->InfoPath) }}" target="_blank" class="inline-block mt-1 px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-sm">
                                <i class="fas fa-download mr-1"></i> {{ $complaint->inquiry->I_filename }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Review Form -->
                <form action="{{ route('assignments.updateReview', $complaint->C_ID) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Update Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="mt-1 block w-full rounded border-gray-300 shadow-sm @error('status') border-red-500 @enderror" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="{{ App\Models\Inquiry::STATUS_IN_PROGRESS }}" 
                                        {{ old('status', $complaint->inquiry->I_Status) == App\Models\Inquiry::STATUS_IN_PROGRESS ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                    <option value="{{ App\Models\Inquiry::STATUS_RESOLVED }}" 
                                        {{ old('status', $complaint->inquiry->I_Status) == App\Models\Inquiry::STATUS_RESOLVED ? 'selected' : '' }}>
                                        Resolved
                                    </option>
                                    <option value="{{ App\Models\Inquiry::STATUS_CLOSED }}" 
                                        {{ old('status', $complaint->inquiry->I_Status) == App\Models\Inquiry::STATUS_CLOSED ? 'selected' : '' }}>
                                        Closed
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700">Review Comment <span class="text-red-500">*</span></label>
                                <textarea name="comment" id="comment" rows="6" class="mt-1 block w-full rounded border-gray-300 shadow-sm @error('comment') border-red-500 @enderror" required>{{ old('comment', $complaint->C_Comment) }}</textarea>
                                @error('comment')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-1">This comment will be visible to MCMC users and recorded in the assignment history.</p>
                            </div>
                        </div>

                        <div>
                            <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
                                <h5 class="text-green-800 font-semibold">Review Guidelines</h5>
                                <ul class="text-sm text-green-700 mt-2 list-disc pl-5">
                                    <li><strong>In Progress:</strong> Currently working on the inquiry</li>
                                    <li><strong>Resolved:</strong> Issue has been addressed/solved</li>
                                    <li><strong>Closed:</strong> Inquiry is complete and closed</li>
                                </ul>
                            </div>
                            <div class="text-sm text-gray-700">
                                <p><strong>Reviewed by:</strong> {{ Auth::user()->A_Name }}</p>
                                <p><strong>Review Date:</strong> {{ date('d M Y') }}</p>
                                <p><strong>Agency:</strong> {{ is_array(Auth::user()->A_Category) ? implode(', ', Auth::user()->A_Category) : Auth::user()->A_Category }}</p>
                            </div>

                            @if($complaint->C_History)
                                <div class="mt-6 p-4 bg-gray-100 border border-gray-300 rounded">
                                    <h5 class="text-sm font-semibold text-gray-800 mb-2">Recent History</h5>
                                    @php
                                        $history = $complaint->getFormattedHistory();
                                        $recentHistory = array_slice($history, -3);
                                    @endphp
                                    @foreach($recentHistory as $entry)
                                        <p class="text-xs text-gray-600">{{ $entry }}</p>
                                    @endforeach
                                    @if(count($history) > 3)
                                        <a href="{{ route('assignments.history', $complaint->C_ID) }}" class="inline-block mt-2 text-sm text-blue-600 hover:underline">
                                            View Full History
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            <i class="fas fa-save mr-2"></i> Update Review
                        </button>
                        <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#status').change(function() {
        var status = $(this).val();
        var commentField = $('#comment');

        switch(status) {
            case '{{ App\Models\Inquiry::STATUS_IN_PROGRESS }}':
                commentField.attr('placeholder', 'Describe the current progress and actions being taken...');
                break;
            case '{{ App\Models\Inquiry::STATUS_RESOLVED }}':
                commentField.attr('placeholder', 'Explain how the inquiry was resolved and what actions were taken...');
                break;
            case '{{ App\Models\Inquiry::STATUS_CLOSED }}':
                commentField.attr('placeholder', 'Provide final comments and confirm the inquiry is complete...');
                break;
            default:
                commentField.attr('placeholder', 'Provide details about the inquiry review, actions taken, or resolution...');
        }
    });
});
</script>
@endsection
