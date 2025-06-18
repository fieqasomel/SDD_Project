@extends('layouts.app')

@section('title', 'Audit Log - MCMC Staff')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">MCMC Audit Log</h1>
                <p class="text-gray-600">Secure audit trail of all MCMC actions on inquiries</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <a href="{{ route('mcmc.inquiries.new') }}" 
                   class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-inbox mr-2"></i>New Inquiries
                </a>
                <a href="{{ route('mcmc.inquiries.processed') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-history mr-2"></i>Previous Inquiries
                </a>
                <a href="{{ route('mcmc.inquiry-reports.generate') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-chart-bar mr-2"></i>Generate Report
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-search mr-2 text-blue-600"></i>Filter Audit Log
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('mcmc.inquiry-activity.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Date From -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar mr-1"></i>Date From
                            </label>
                            <input type="date" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar mr-1"></i>Date To
                            </label>
                            <input type="date" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>

                        <!-- MCMC Staff Filter -->
                        <div>
                            <label for="mcmc_staff" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1"></i>MCMC Staff
                            </label>
                            <select id="mcmc_staff" 
                                    name="mcmc_staff"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Staff</option>
                                @foreach($mcmcStaff as $staff)
                                    <option value="{{ $staff->M_ID }}" {{ request('mcmc_staff') == $staff->M_ID ? 'selected' : '' }}>{{ $staff->M_Name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Filter -->
                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-gavel mr-1"></i>Action
                            </label>
                            <select id="action" 
                                    name="action"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Actions</option>
                                <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                        <a href="{{ route('inquiries.mcmc.audit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Log Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-list mr-2 text-blue-600"></i>Audit Trail
                    <span class="ml-2 text-sm text-gray-500">({{ $auditLogs->count() }} records)</span>
                </h3>
            </div>
            
            @if($auditLogs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Information</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MCMC Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Decision Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($auditLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $log->I_ID }}</div>
                                        <div class="text-sm text-gray-600 max-w-xs truncate">{{ $log->I_Title }}</div>
                                        <div class="text-xs text-blue-600 mt-1">
                                            <i class="fas fa-tag mr-1"></i>{{ $log->I_Category }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $log->publicUser->PU_Name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-600">{{ $log->publicUser->PU_Email ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">
                                            Submitted: {{ \Carbon\Carbon::parse($log->I_Date)->format('d M Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $log->mcmc_processed_by }}</div>
                                        @if($log->I_Status == 'Approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @elseif($log->I_Status == 'Rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                <i class="fas fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        @if($log->rejection_reason)
                                            <div class="text-sm text-red-600 mb-1">
                                                <strong>Reason:</strong> {{ $log->rejection_reason }}
                                            </div>
                                        @endif
                                        @if($log->mcmc_notes)
                                            <div class="text-sm text-gray-600">
                                                <strong>Notes:</strong> 
                                                <span class="truncate block" title="{{ $log->mcmc_notes }}">{{ $log->mcmc_notes }}</span>
                                            </div>
                                        @endif
                                        @if(!$log->rejection_reason && !$log->mcmc_notes)
                                            <span class="text-sm text-gray-400">No additional details</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->mcmc_processed_at)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->mcmc_processed_at)->format('H:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('inquiries.show', $log->I_ID) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                       title="View Inquiry Details">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $auditLogs->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-clipboard-list text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Audit Records</h3>
                    <p class="text-gray-600">No MCMC actions have been recorded yet or match your search criteria.</p>
                </div>
            @endif
        </div>

        <!-- Audit Information -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Audit Trail Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>This audit log maintains a secure record of all MCMC staff actions on inquiries, including:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Staff member who processed the inquiry</li>
                            <li>Decision made (Approved/Rejected)</li>
                            <li>Timestamp of the action</li>
                            <li>Rejection reasons (if applicable)</li>
                            <li>Additional notes from MCMC staff</li>
                        </ul>
                        <p class="mt-2">All records are immutable and maintained for compliance and transparency purposes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection