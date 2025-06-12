@extends('layouts.app')

@section('title', 'Manage Assignments')

@section('content')
<div class="px-6 py-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Assignment Management</h2>
        <a href="{{ route('assignments.report') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
            <i class="fas fa-chart-bar mr-2"></i> Generate Report
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['total_assignments'] }}</h3>
            <p>Total Assignments</p>
        </div>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['pending'] }}</h3>
            <p>Pending</p>
        </div>
        <div class="bg-blue-200 text-blue-900 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['in_progress'] }}</h3>
            <p>In Progress</p>
        </div>
        <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['resolved'] }}</h3>
            <p>Resolved</p>
        </div>
        <div class="bg-gray-200 text-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['closed'] }}</h3>
            <p>Closed</p>
        </div>
        <div class="bg-green-200 text-green-900 p-4 rounded-lg shadow">
            <h3 class="text-xl font-bold">{{ $stats['this_month'] }}</h3>
            <p>This Month</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <form method="GET" action="{{ route('assignments.index') }}" class="grid md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="agency" class="block text-sm font-medium text-gray-700">Agency</label>
                <select name="agency" id="agency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Agencies</option>
                    @foreach($agencies as $agency)
                        <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>
                            {{ $agency->A_Name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </div>
        </form>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        @if($assignments->isEmpty())
            <div class="p-6 text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                No assignments found. 
                <a href="{{ route('inquiries.index') }}" class="text-blue-600 hover:underline">View unassigned inquiries</a> to create new assignments.
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inquiry Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verification</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($assignments as $assignment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $assignment->C_ID }}<br>
                            <span class="text-xs text-gray-500">{{ $assignment->inquiry->I_ID }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium">{{ Str::limit($assignment->inquiry->I_Title, 40) }}</div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $assignment->inquiry->I_Category }}</span><br>
                            <span class="text-xs text-gray-500">by {{ $assignment->inquiry->publicUser->PU_Name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium">{{ $assignment->agency->A_Name }}</div>
                            <span class="text-xs text-gray-500">{{ $assignment->agency->A_Category }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $assignment->C_AssignedDate->format('d M Y') }}<br>
                            <span class="text-xs text-gray-500">by {{ $assignment->mcmc->M_Name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-xs inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                {{ $assignment->inquiry->I_Status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-xs inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                {{ $assignment->C_VerificationStatus ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php $days = $assignment->C_AssignedDate->diffInDays(now()); @endphp
                            <span class="text-xs px-2 py-1 rounded {{ $days > 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $days }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-1">
                            <a href="{{ route('assignments.view', $assignment->C_ID) }}" class="inline-flex items-center justify-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('assignments.history', $assignment->C_ID) }}" class="inline-flex items-center justify-center px-2 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded">
                                <i class="fas fa-history"></i>
                            </a>
                            <a href="{{ route('assignments.reassign', $assignment->C_ID) }}" class="inline-flex items-center justify-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $assignments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const agencySelect = document.getElementById('agency');
    if (agencySelect) {
        agencySelect.addEventListener('change', function () {
            this.form.submit();
        });
    }
});
</script>
@endsection