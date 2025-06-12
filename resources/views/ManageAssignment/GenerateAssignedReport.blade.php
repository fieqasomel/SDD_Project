@extends('layouts.app')

@section('title', 'Assignment Report')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold">Assignment Report</h3>
            <div class="space-x-2">
                <a href="{{ route('assignments.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md inline-flex items-center transition duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assignments
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md inline-flex items-center transition duration-200">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- Report Filters -->
            <div class="mb-6">
                <div class="border rounded-lg">
                    <div class="px-4 py-2 border-b bg-gray-100 font-medium">Report Filters</div>
                    <div class="p-4">
                        <form method="GET" action="{{ route('assignments.report') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                    <input type="date" name="date_from" id="date_from" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                    <input type="date" name="date_to" id="date_to" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ request('date_to', now()->endOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div>
                                    <label for="agency" class="block text-sm font-medium text-gray-700 mb-1">Agency</label>
                                    <select name="agency" id="agency" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white">
                                        <option value="">All Agencies</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>{{ $agency->A_Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-filter mr-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ $stats['total_assignments'] }}</div>
                    <div class="text-blue-100 text-sm">Total Assignments</div>
                </div>
                <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ $stats['pending'] }}</div>
                    <div class="text-yellow-100 text-sm">Pending</div>
                </div>
                <div class="bg-indigo-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ $stats['in_progress'] }}</div>
                    <div class="text-indigo-100 text-sm">In Progress</div>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ $stats['resolved'] }}</div>
                    <div class="text-green-100 text-sm">Resolved</div>
                </div>
                <div class="bg-gray-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ $stats['closed'] }}</div>
                    <div class="text-gray-100 text-sm">Closed</div>
                </div>
                <div class="bg-blue-400 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="text-2xl font-bold mb-1">{{ number_format(($stats['resolved'] / max($stats['total_assignments'], 1)) * 100, 1) }}%</div>
                    <div class="text-blue-100 text-sm">Resolution Rate</div>
                </div>
            </div>

            <!-- Agency Statistics -->
            @if($agencyStats->isNotEmpty())
            <div class="mb-6">
                <div class="border rounded-lg">
                    <div class="px-4 py-2 border-b bg-gray-100 font-medium">Assignments by Agency</div>
                    <div class="p-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Agency</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Total Assignments</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($agencyStats as $stat)
                                @php
                                    $percentage = ($stat['count'] / max($stats['total_assignments'], 1)) * 100;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-200">{{ $stat['agency']->A_Name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-200">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $stat['agency']->A_Category }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $stat['count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ number_format($percentage, 1) }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Assignment Details -->
            <div>
                <div class="border rounded-lg">
                    <div class="px-4 py-2 border-b bg-gray-100 font-medium">Assignment Details</div>
                    <div class="p-4 overflow-x-auto">
                        @if($assignments->isEmpty())
                        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">No assignments found for the selected criteria.</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned to</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Active</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted by</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($assignments as $assignment)
                                @php $days = $assignment->C_AssignedDate->diffInDays(now()); @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $assignment->C_ID }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment->inquiry->I_ID }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $assignment->inquiry->I_Title }}">{{ Str::limit($assignment->inquiry->I_Title, 30) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $assignment->inquiry->I_Category }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $assignment->agency->A_Name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment->C_AssignedDate->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $assignment->inquiry->I_Status }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $days > 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $days }} days</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment->inquiry->publicUser->PU_Name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Report Summary -->
            <div class="mt-6">
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-medium text-gray-900">Report Summary</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Report Generated:</span>
                                    <span class="text-sm text-gray-900">{{ now()->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Generated by:</span>
                                    <span class="text-sm text-gray-900">{{ Auth::user()->M_Name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Date Range:</span>
                                    <span class="text-sm text-gray-900">{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }} to {{ request('date_to', now()->endOfMonth()->format('Y-m-d')) }}</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total Records:</span>
                                    <span class="text-sm text-gray-900">{{ $assignments->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Filter Applied:</span>
                                    <span class="text-sm text-gray-900">{{ request('agency') ? $agencies->where('A_ID', request('agency'))->first()->A_Name ?? 'All Agencies' : 'All Agencies' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">System:</span>
                                    <span class="text-sm text-gray-900">MCMC Assignment Management System</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    /* Print-specific styles */
    body {
        font-size: 12px;
    }
    
    .shadow-md, .shadow-lg, .shadow-sm {
        box-shadow: none !important;
    }
    
    .hover\:bg-gray-50:hover,
    .hover\:shadow-lg:hover,
    .transition-colors,
    .transition-shadow,
    .transition-all {
        transition: none !important;
    }
}
</style>
@endsection
