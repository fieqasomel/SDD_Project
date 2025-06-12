@extends('layouts.app')

@section('title', 'Assign Inquiry')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Assign Inquiry to Agency</h3>
            <a href="{{ route('assignments.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600">
                <i class="fas fa-arrow-left mr-1"></i> Back to Assignments
            </a>
        </div>

        <div class="p-6">
            <!-- Inquiry Details -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                <h4 class="text-lg font-semibold text-blue-700 mb-2">Inquiry Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p><strong>Inquiry ID:</strong> {{ $inquiry->I_ID }}</p>
                        <p><strong>Title:</strong> {{ $inquiry->I_Title }}</p>
                        <p><strong>Category:</strong> <span class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $inquiry->I_Category }}</span></p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded bg-{{ $inquiry->getStatusBadgeColor() === 'success' ? 'green' : ($inquiry->getStatusBadgeColor() === 'danger' ? 'red' : 'yellow') }}-500 text-white">{{ $inquiry->I_Status }}</span></p>
                        <p><strong>Date:</strong> {{ $inquiry->I_Date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p><strong>Submitted by:</strong> {{ $inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</p>
                        <p><strong>Source:</strong> {{ $inquiry->I_Source ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <strong>Description:</strong>
                    <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $inquiry->I_Description }}</p>
                </div>
            </div>

            <!-- Assignment Form -->
            <form action="{{ route('assignments.store', $inquiry->I_ID) }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label for="agency_id" class="block font-medium text-gray-700">Select Agency <span class="text-red-500">*</span></label>
                            <select name="agency_id" id="agency_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('agency_id') border-red-500 @enderror" required>
                                <option value="">-- Select Agency --</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->A_ID }}" {{ old('agency_id') == $agency->A_ID ? 'selected' : '' }}>
                                        {{ $agency->A_Name }} - {{ $agency->A_Category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Only agencies that handle "<strong>{{ $inquiry->I_Category }}</strong>" category are shown.</p>
                        </div>
                        <div>
                            <label for="comment" class="block font-medium text-gray-700">Assignment Comment</label>
                            <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('comment') border-red-500 @enderror" placeholder="Add any comments or instructions for the assigned agency...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow">
                            <h5 class="text-yellow-800 font-semibold mb-2">Assignment Info</h5>
                            <p><strong>Assignment Date:</strong> {{ date('d M Y') }}</p>
                            <p><strong>Assigned by:</strong> {{ Auth::user()->M_Name }}</p>
                            <p><strong>Category Match:</strong> Agencies shown match the inquiry category</p>
                            <div class="mt-3 text-sm text-blue-800 bg-blue-100 p-2 rounded">
                                <i class="fas fa-info-circle mr-1"></i> Inquiry status will update to <strong>"In Progress"</strong> once assigned.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded shadow">
                        <i class="fas fa-check mr-2"></i> Assign Inquiry
                    </button>
                    <a href="{{ route('assignments.index') }}" class="inline-flex items-center bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium px-4 py-2 rounded">
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
    $(document).ready(function () {
        $('#agency_id').select2({
            theme: 'bootstrap4',
            placeholder: '-- Select Agency --'
        });
    });
</script>
@endsection
