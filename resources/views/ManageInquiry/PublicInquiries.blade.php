@extends('layouts.app')

@section('title', 'Public Inquiries - MySebenarnya System')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Public Inquiries</h1>
                <p class="text-gray-600">Browse inquiries submitted by the community</p>
            </div>
            <div class="flex gap-3 mt-4 sm:mt-0">
                <a href="{{ route('inquiries.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>My Inquiries
                </a>
                <a href="{{ route('inquiries.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>New Inquiry
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Info Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Privacy Notice</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <p>Personal information of inquiry submitters is protected. Only general inquiry details are shown for community reference.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Public Inquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Public</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $inquiries->total() ?? $inquiries->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-globe text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $inquiries->where('I_Status', 'Pending')->count() }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-400 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">In Progress</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $inquiries->where('I_Status', 'In Progress')->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-spinner text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Resolved</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $inquiries->where('I_Status', 'Resolved')->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-search mr-2 text-blue-600"></i>Search & Filter
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('inquiries.public') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Text -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-search mr-1"></i>Search Text
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by ID, title, category..."
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
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
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
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <div class="flex gap-2 w-full">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                    <i class="fas fa-search mr-1"></i>Search
                                </button>
                                <a href="{{ route('inquiries.public') }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm"
                                   title="Clear filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                </form>
                
                <!-- Active Filters -->
                @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Active filters:</span>
                            
                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('category'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Category: {{ request('category') }}
                                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('status'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Status: {{ request('status') }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif

                            @if(request('date_from'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    From: {{ request('date_from') }}
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif

                            @if(request('date_to'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    To: {{ request('date_to') }}
                                    <a href="{{ request()->fullUrlWithQuery(['date_to' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            <a href="{{ route('inquiries.public') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                                <i class="fas fa-times mr-1"></i>Clear all
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Public Inquiries Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-globe mr-2 text-blue-600"></i>Public Inquiries
                    @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                        <span class="ml-2 text-sm text-gray-500">({{ $inquiries->count() }} found)</span>
                    @else
                        <span class="ml-2 text-sm text-gray-500">({{ $inquiries->count() }} total)</span>
                    @endif
                </h3>
            </div>
            <div class="p-6">
                @if($inquiries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($inquiry->I_Title, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                                    'Resolved' => 'bg-green-100 text-green-800',
                                                    'Closed' => 'bg-gray-100 text-gray-800',
                                                    'Rejected' => 'bg-red-100 text-red-800'
                                                ];
                                                $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                                {{ $inquiry->I_Status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Date ? date('Y-m-d', strtotime($inquiry->I_Date)) : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- Privacy Protection: Don't show actual user details -->
                                            <span class="text-gray-500 italic">Anonymous User</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View Button (Limited Info) -->
                                                <button class="view-inquiry-btn inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                                       data-inquiry-id="{{ $inquiry->I_ID }}"
                                                       data-inquiry-title="{{ htmlspecialchars($inquiry->I_Title ?? '') }}"
                                                       data-inquiry-category="{{ htmlspecialchars($inquiry->I_Category ?? '') }}"
                                                       data-inquiry-status="{{ htmlspecialchars($inquiry->I_Status ?? '') }}"
                                                       data-inquiry-date="{{ $inquiry->I_Date ?? '' }}"
                                                       data-inquiry-description="{{ htmlspecialchars($inquiry->I_Description ?? '') }}">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex justify-center">
                        {{ $inquiries->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                                <i class="fas fa-search text-5xl"></i>
                            @else
                                <i class="fas fa-globe text-5xl"></i>
                            @endif
                        </div>
                        
                        @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Results Found</h3>
                            <p class="text-gray-600 mb-4">No public inquiries match your search criteria. Try adjusting your filters.</p>
                            <a href="{{ route('inquiries.public') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Clear Filters
                            </a>
                        @else
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Public Inquiries</h3>
                            <p class="text-gray-600 mb-4">There are no public inquiries available at the moment.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Public Inquiry Details -->
<div id="publicInquiryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border max-w-2xl shadow-2xl rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 flex items-center" id="modalTitle">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Inquiry Details
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-all duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div id="modalContent" class="text-sm text-gray-700">
                <!-- Content will be loaded here -->
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                <button onclick="closeModal()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-submit form when dropdown filters change
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('status');
    
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Auto-submit search after typing pause
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 1000); // Submit after 1 second of no typing
        });
    }
    
    // Add event listeners to view buttons
    const viewButtons = document.querySelectorAll('.view-inquiry-btn');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const inquiryId = this.dataset.inquiryId;
            viewPublicInquiry(inquiryId);
        });
    });
});

function viewPublicInquiry(inquiryId) {
    // Get modal element
    const modal = document.getElementById('publicInquiryModal');
    if (!modal) {
        alert('Modal element not found!');
        return;
    }
    
    // Try to find button to get data from attributes
    const button = document.querySelector(`button[data-inquiry-id="${inquiryId}"]`);
    if (!button) {
        alert('Inquiry data not found!');
        return;
    }
    
    // Get data from button attributes
    const inquiry = {
        I_ID: button.dataset.inquiryId,
        I_Title: button.dataset.inquiryTitle,
        I_Category: button.dataset.inquiryCategory,
        I_Status: button.dataset.inquiryStatus,
        I_Date: button.dataset.inquiryDate,
        I_Description: button.dataset.inquiryDescription
    };
    
    // Show modal with data
    if (inquiry && inquiry.I_ID) {
        document.getElementById('modalTitle').innerHTML = `
            <i class="fas fa-eye text-blue-600 mr-2"></i>
            ${inquiry.I_ID} - ${inquiry.I_Title}
        `;
        document.getElementById('modalContent').innerHTML = `
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                            <i class="fas fa-hashtag mr-1"></i>Inquiry ID
                        </label>
                        <p class="text-sm font-bold text-gray-900">${inquiry.I_ID}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                            <i class="fas fa-calendar mr-1"></i>Date Submitted
                        </label>
                        <p class="text-sm font-bold text-gray-900">${inquiry.I_Date || 'N/A'}</p>
                    </div>
                </div>

                <!-- Title -->
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <label class="block text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                        <i class="fas fa-heading mr-1"></i>Title
                    </label>
                    <p class="text-sm font-semibold text-gray-900">${inquiry.I_Title}</p>
                </div>

                <!-- Category & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            <i class="fas fa-tag mr-1"></i>Category
                        </label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            ${inquiry.I_Category}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            <i class="fas fa-flag mr-1"></i>Status
                        </label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ${getStatusClass(inquiry.I_Status)}">
                            ${inquiry.I_Status}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        <i class="fas fa-align-left mr-1"></i>Description
                    </label>
                    <div class="bg-gray-50 p-4 rounded-lg border max-h-40 overflow-y-auto">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">${inquiry.I_Description || 'No description available'}</p>
                    </div>
                </div>

                <!-- Privacy Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800">Privacy Protected</h4>
                            <p class="text-xs text-blue-700 mt-1">
                                Personal information of the inquiry submitter is protected and not displayed for privacy reasons.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('publicInquiryModal').classList.remove('hidden');
        // Add smooth scroll to top of modal
        document.getElementById('publicInquiryModal').scrollTop = 0;
    } else {
        // Show error in modal instead of alert
        document.getElementById('modalTitle').innerHTML = `
            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
            Error Loading Inquiry
        `;
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-400 mb-4">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Inquiry Not Found</h3>
                <p class="text-gray-600 mb-4">The inquiry details could not be loaded. Please refresh the page and try again.</p>
                <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-refresh mr-2"></i>Refresh Page
                </button>
            </div>
        `;
        document.getElementById('publicInquiryModal').classList.remove('hidden');
    }
}

function closeModal() {
    document.getElementById('publicInquiryModal').classList.add('hidden');
}

function getStatusClass(status) {
    const classes = {
        'Pending': 'bg-yellow-100 text-yellow-800',
        'In Progress': 'bg-blue-100 text-blue-800',
        'Resolved': 'bg-green-100 text-green-800',
        'Closed': 'bg-gray-100 text-gray-800',
        'Rejected': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

// Close modal when clicking outside
document.getElementById('publicInquiryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('publicInquiryModal').classList.contains('hidden')) {
        closeModal();
    }
});

// Add loading indicator
function showModalLoading() {
    document.getElementById('modalTitle').innerHTML = `
        <i class="fas fa-spinner fa-spin text-blue-600 mr-2"></i>
        Loading...
    `;
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-8">
            <div class="text-blue-400 mb-4">
                <i class="fas fa-spinner fa-spin text-4xl"></i>
            </div>
            <p class="text-gray-600">Loading inquiry details...</p>
        </div>
    `;
    document.getElementById('publicInquiryModal').classList.remove('hidden');
}


</script>

@endsection