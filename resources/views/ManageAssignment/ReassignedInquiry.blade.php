@extends('layouts.app')

@section('title', 'Reassign Inquiry')

@section('content')
<div class="px-6 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Reassign Inquiry to Different Agency</h2>
                <a href="{{ route('assignments.view', $complaint->C_ID) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-sm text-gray-700 rounded hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Assignment
                </a>
            </div>

            <!-- Current Assignment Info -->
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-700 mb-3">Current Assignment</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p><strong>Inquiry ID:</strong> {{ $complaint->inquiry->I_ID }}</p>
                        <p><strong>Title:</strong> {{ $complaint->inquiry->I_Title }}</p>
                        <p><strong>Category:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">{{ $complaint->inquiry->I_Category }}</span></p>
                        <p><strong>Current Status:</strong> <span class="px-2 py-0.5 rounded text-xs text-white bg-{{ $complaint->inquiry->getStatusBadgeColor() }}">{{ $complaint->inquiry->I_Status }}</span></p>
                    </div>
                    <div>
                        <p><strong>Currently Assigned to:</strong> {{ $complaint->agency->A_Name }}</p>
                        <p><strong>Assignment Date:</strong> {{ $complaint->C_AssignedDate->format('d M Y') }}</p>
                        <p><strong>Days Since Assignment:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">{{ $complaint->C_AssignedDate->diffInDays(now()) }} days</span></p>
                        <p><strong>Assigned by:</strong> {{ $complaint->mcmc->M_Name }}</p>
                    </div>
                </div>

                @if($complaint->C_Comment)
                    <div class="mt-4">
                        <strong class="text-sm text-gray-800">Previous Comment:</strong>
                        <p class="text-gray-600 text-sm mt-1">{{ $complaint->C_Comment }}</p>
                    </div>
                @endif
            </div>

            <!-- Reassignment Form -->
            <form action="{{ route('assignments.storeReassignment', $complaint->C_ID) }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label for="agency_id" class="block text-sm font-medium text-gray-700">Select New Agency <span class="text-red-500">*</span></label>
                            <select name="agency_id" id="agency_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('agency_id') border-red-500 @enderror">
                                <option value="">-- Select New Agency --</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->A_ID }}" {{ old('agency_id') == $agency->A_ID ? 'selected' : '' }}>
                                        {{ $agency->A_Name }} - {{ $agency->A_Category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Only agencies that handle "{{ $complaint->inquiry->I_Category }}" category are shown (excluding current agency).</p>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Reassignment Reason <span class="text-red-500">*</span></label>
                            <textarea name="comment" id="comment" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('comment') border-red-500 @enderror"
                                placeholder="Please provide a reason for reassignment...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">This reason will be recorded in the assignment history.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-red-50 border border-red-200 p-4 rounded-md">
                            <h4 class="text-red-700 font-semibold mb-2">Reassignment Information</h4>
                            <p class="text-sm"><strong>Reassignment Date:</strong> {{ date('d M Y') }}</p>
                            <p class="text-sm"><strong>Reassigned by:</strong> {{ Auth::user()->M_Name }}</p>
                            <p class="text-sm"><strong>Original Agency:</strong> {{ $complaint->agency->A_Name }}</p>

                            <div class="mt-3 bg-yellow-100 border-l-4 border-yellow-500 p-3 rounded text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Warning:</strong> Reassigning will transfer this inquiry to a different agency. The assignment history will be preserved.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center mt-6">
                    <button type="submit" onclick="return confirm('Are you sure you want to reassign this inquiry?')"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded shadow-sm">
                        <i class="fas fa-exchange-alt mr-2"></i> Reassign Inquiry
                    </button>
                    <a href="{{ route('assignments.view', $complaint->C_ID) }}"
                        class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded shadow-sm">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#agency_id').select2({
        theme: 'bootstrap4',
        placeholder: '-- Select New Agency --'
    });
});
</script>
@endsection
