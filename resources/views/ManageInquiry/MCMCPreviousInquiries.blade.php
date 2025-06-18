@extends('layouts.app')

@section('title', 'Previous Inquiries - MCMC Staff')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Previous Inquiries</h1>
                <p class="text-gray-600">View all previously filtered inquiries with secure audit trail</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <a href="{{ route('mcmc.inquiries.new') }}" 
                   class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-inbox mr-2"></i>New Inquiries
                </a>
                <a href="{{ route('mcmc.inquiry-activity.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-clipboard-list mr-2"></i>Audit Log
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
                    <i class="fas fa-search mr-2 text-blue-600"></i>Search & Filter
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('mcmc.inquiries.processed') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Search Text -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-search mr-1"></i>Search Text
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by ID, title..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-tag mr-1"></i>Category
                            </label>
                            <select id="category" 
                                    name="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-flag mr-1"></i>Status
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Agency Filter -->
                        <div>
                            <label for="agency" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-building mr-1"></i>Agency
                            </label>
                            <select id="agency" 
                                    name="agency"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Agencies</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->A_ID }}" {{ request('agency') == $agency->A_ID ? 'selected' : '' }}>{{ $agency->A_Name }}</option>
                                @endforeach
                            </select>
                        </div>

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
                    </div>

                    <!-- Search Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                            <i class="fas fa-search mr-1"></i>Search
                        </button>
                        <a href="{{ route('inquiries.mcmc.previous') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-blue-600"></i>Previous Inquiries
                    <span class="ml-2 text-sm text-gray-500">({{ $previousInquiries->count() }} found)</span>
                </h3>
            </div>
            
            @if($previousInquiries->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MCMC Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($previousInquiries as $inquiry)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</div>
                                        <div class="text-sm text-gray-600 max-w-xs truncate">{{ $inquiry->I_Title }}</div>
                                        @if($inquiry->I_filename)
                                            <div class="text-xs text-blue-600 mt-1">
                                                <i class="fas fa-paperclip mr-1"></i>Has Attachment
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->publicUser->PU_Name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-600">{{ $inquiry->publicUser->PU_Email ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $inquiry->I_Category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($inquiry->I_Status == 'Approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>{{ $inquiry->I_Status }}
                                        </span>
                                    @elseif($inquiry->I_Status == 'Rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>{{ $inquiry->I_Status }}
                                        </span>
                                    @elseif($inquiry->I_Status == 'In Progress')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-spinner mr-1"></i>{{ $inquiry->I_Status }}
                                        </span>
                                    @elseif($inquiry->I_Status == 'Resolved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>{{ $inquiry->I_Status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $inquiry->I_Status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div>{{ \Carbon\Carbon::parse($inquiry->I_Date)->format('d M Y') }}</div>
                                        @if($inquiry->mcmc_processed_at)
                                            <div class="text-xs text-gray-500">
                                                Processed: {{ \Carbon\Carbon::parse($inquiry->mcmc_processed_at)->format('d M Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        @if($inquiry->mcmc_processed_by)
                                            <div class="text-gray-900 font-medium">{{ $inquiry->mcmc_processed_by }}</div>
                                        @endif
                                        @if($inquiry->mcmc_notes)
                                            <div class="text-xs text-gray-500 max-w-xs truncate" title="{{ $inquiry->mcmc_notes }}">
                                                <i class="fas fa-sticky-note mr-1"></i>Has Notes
                                            </div>
                                        @endif
                                        @if($inquiry->rejection_reason)
                                            <div class="text-xs text-red-600 max-w-xs truncate" title="{{ $inquiry->rejection_reason }}">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $inquiry->rejection_reason }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                           title="View Details">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        @if($inquiry->I_Status == 'Approved')
                                            <a href="{{ route('assignments.assign', $inquiry->I_ID) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                               title="Assign to Agency">
                                                <i class="fas fa-share mr-1"></i>Assign
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $previousInquiries->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-history text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Previous Inquiries</h3>
                    <p class="text-gray-600">No inquiries have been processed yet or match your search criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection