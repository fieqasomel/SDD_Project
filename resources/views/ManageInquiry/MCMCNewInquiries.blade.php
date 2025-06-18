@extends('layouts.app')

@section('title', 'New Inquiries - MCMC Staff')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">New Inquiries</h1>
                <p class="text-gray-600">View and validate newly submitted inquiries from public users</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total New -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Total New</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_new'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-inbox text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Today -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Today</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['today_new'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-1">This Week</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['this_week_new'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-calendar-week text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">This Month</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['this_month_new'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-calendar-alt text-2xl text-green-600"></i>
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
                <form method="GET" action="{{ route('mcmc.inquiries.new') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
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

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <div class="flex gap-2 w-full">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                    <i class="fas fa-search mr-1"></i>Search
                                </button>
                                <a href="{{ route('mcmc.inquiries.new') }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm"
                                   title="Clear filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-2 text-blue-600"></i>New Inquiries List
                    <span class="ml-2 text-sm text-gray-500">({{ $newInquiries->count() ?? 0 }} found)</span>
                </h3>
            </div>
            
            @if(($newInquiries->count() ?? 0) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Information</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($newInquiries ?? [] as $inquiry)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->I_ID ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-600 max-w-xs truncate">{{ $inquiry->I_Title ?? 'N/A' }}</div>
                                        @if($inquiry->I_filename ?? false)
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
                                        @if($inquiry->publicUser->PU_Phone ?? false)
                                            <div class="text-xs text-gray-500">{{ $inquiry->publicUser->PU_Phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $inquiry->I_Category ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>{{ $inquiry->I_Status ?? 'New' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('mcmc.inquiries.show', $inquiry->I_ID ?? 0) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        
                                        <!-- Validate Button -->
                                        <button onclick="validateInquiry({{ $inquiry->I_ID ?? 0 }})" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                            <i class="fas fa-check mr-1"></i>Validate
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($newInquiries ?? collect(), 'links'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $newInquiries->links() }}
                </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                        <i class="fas fa-inbox text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No New Inquiries</h3>
                    <p class="text-gray-500">There are no new inquiries to display at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Validation Modal -->
<div id="validateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Validate Inquiry</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to validate this inquiry? This action will mark it as validated and ready for assignment.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmValidate" 
                        class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                    Validate
                </button>
                <button onclick="closeValidateModal()" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentInquiryId = null;

function validateInquiry(inquiryId) {
    currentInquiryId = inquiryId;
    document.getElementById('validateModal').classList.remove('hidden');
}

function closeValidateModal() {
    document.getElementById('validateModal').classList.add('hidden');
    currentInquiryId = null;
}

document.getElementById('confirmValidate').addEventListener('click', function() {
    if (currentInquiryId) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mcmc/inquiries/${currentInquiryId}/validate`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
});

// Close modal when clicking outside
document.getElementById('validateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeValidateModal();
    }
});
</script>
@endsection