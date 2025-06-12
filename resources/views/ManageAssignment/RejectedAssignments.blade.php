@extends('layouts.app')

@section('title', 'Rejected Assignments')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">
    <div class="mb-4">
        <div class="flex justify-between items-center bg-white shadow-md p-4 rounded-xl">
            <h3 class="text-xl font-semibold text-gray-700">Rejected Assignments - Requiring Reassignment</h3>
            <a href="{{ route('assignments.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Assignments
            </a>
        </div>
    </div>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-6">
        <div class="font-bold text-lg mb-1"><i class="fas fa-exclamation-triangle"></i> Attention MCMC Staff</div>
        <p class="text-sm">The following assignments have been rejected by agencies as they fall outside their scope. Please review the rejection reasons and reassign to appropriate agencies.</p>
    </div>

    <div class="mb-6 bg-white p-4 rounded-xl shadow">
        <h5 class="text-lg font-medium text-gray-700 mb-4">Filter Rejected Assignments</h5>
        <form method="GET" action="{{ route('assignments.rejected') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="agency" class="block text-sm font-medium text-gray-600">Rejecting Agency</label>
                    <select name="agency" id="agency" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Agencies</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>
                                {{ $agency->A_Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-600">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-600">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ request('date_to') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-xl p-4">
        @if($rejectedAssignments->isEmpty())
            <div class="text-green-600 font-semibold">
                <i class="fas fa-check-circle"></i> No rejected assignments found. All assignments have been accepted by agencies.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Assignment ID</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Inquiry Details</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rejected by Agency</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rejection Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rejection Reason</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Original Assignment</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rejectedAssignments as $assignment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $assignment->C_ID }}<br>
                                <span class="text-xs text-gray-500">{{ $assignment->inquiry->I_ID }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ Str::limit($assignment->inquiry->I_Title, 40) }}</div>
                                <span class="inline-block mt-1 text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $assignment->inquiry->I_Category }}</span>
                                <div class="text-xs text-gray-500">by {{ $assignment->inquiry->publicUser->PU_Name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-red-700">{{ $assignment->agency->A_Name }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->agency->A_Category }}</div>
                                <span class="inline-block mt-1 text-xs px-2 py-1 bg-red-100 text-red-800 rounded">Rejected</span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $assignment->C_VerificationDate->format('d M Y') }}<br>
                                <span class="text-xs text-gray-500">{{ $assignment->C_VerificationDate->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ Str::limit($assignment->C_RejectionReason, 100) }}
                                @if(strlen($assignment->C_RejectionReason) > 100)
                                    <a href="#" class="text-blue-600 underline ml-1" data-toggle="modal" data-target="#reasonModal{{ $assignment->C_ID }}">read more</a>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $assignment->C_AssignedDate->format('d M Y') }}<br>
                                <span class="text-xs text-gray-500">by {{ $assignment->mcmc->M_Name }}</span>
                                <div class="text-xs text-blue-600 mt-1">{{ $assignment->C_AssignedDate->diffInDays($assignment->C_VerificationDate) }} days to reject</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="space-y-1">
                                    <a href="{{ route('assignments.view', $assignment->C_ID) }}" class="block text-sm text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-center">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('assignments.reassign', $assignment->C_ID) }}" class="block text-sm text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-center">
                                        <i class="fas fa-exchange-alt"></i> Reassign
                                    </a>
                                    <a href="{{ route('assignments.history', $assignment->C_ID) }}" class="block text-sm text-white bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded text-center">
                                        <i class="fas fa-history"></i> History
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $rejectedAssignments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#agency').change(function() {
        if ($(this).val() !== '') {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection