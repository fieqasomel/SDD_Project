@extends('layouts.app')

@section('title', 'Activity Log - MCMC')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">MCMC Activity Log</h1>
            <a href="{{ route('mcmc.inquiries.new') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Inquiries
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <form method="GET" action="{{ route('mcmc.inquiry-activity.index') }}" class="flex flex-wrap gap-4">
                <div>
                    <select name="action" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Actions</option>
                        <option value="validate_inquiry" {{ request('action') == 'validate_inquiry' ? 'selected' : '' }}>Validate Inquiry</option>
                        <option value="reject_inquiry" {{ request('action') == 'reject_inquiry' ? 'selected' : '' }}>Reject Inquiry</option>
                        <option value="mark_non_serious" {{ request('action') == 'mark_non_serious' ? 'selected' : '' }}>Mark Non-Serious</option>
                        <option value="assign_inquiry" {{ request('action') == 'assign_inquiry' ? 'selected' : '' }}>Assign Inquiry</option>
                        <option value="generate_report" {{ request('action') == 'generate_report' ? 'selected' : '' }}>Generate Report</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 rounded-md">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                <a href="{{ route('mcmc.inquiry-activity.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Clear
                </a>
            </form>
        </div>

        <!-- Activity Log Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $activity->processor_name ?? 'Unknown User' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $activity->user_id }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $activity->action == 'validate_inquiry' ? 'bg-green-100 text-green-800' : 
                                       ($activity->action == 'reject_inquiry' ? 'bg-red-100 text-red-800' : 
                                       ($activity->action == 'mark_non_serious' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $activity->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="mt-6">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection