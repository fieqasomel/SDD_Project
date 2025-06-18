@extends('layouts.app')

@section('title', 'Search Inquiries')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                @if(auth()->guard('mcmc')->check())
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">MCMC Search & Filter Inquiries</h1>
                    <p class="text-gray-600">Search inquiries and validate genuine submissions (MCMC Staff Only)</p>
                @else
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Inquiries</h1>
                    <p class="text-gray-600">Find specific inquiries using advanced search filters</p>
                @endif
            </div>
            <div class="flex space-x-3 mt-4 sm:mt-0">
                @if(auth()->guard('mcmc')->check())
                    <a href="{{ route('mcmc.inquiries.new') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-list mr-2"></i>New Inquiries
                    </a>
                    <a href="{{ route('mcmc.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                @else
                    <a href="{{ route('inquiries.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                @endif
            </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>Search Filters
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('inquiries.search') }}" class="space-y-6">
                    <!-- Search Text -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i>Search Text
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by ID, title, description, or category..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-1"></i>Category
                            </label>
                            <select id="category" 
                                    name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag mr-1"></i>Status
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1"></i>Date From
                            </label>
                            <input type="date" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1"></i>Date To
                            </label>
                            <input type="date" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        
                        <a href="{{ route('inquiries.search') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-redo mr-2"></i>Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-search mr-2 text-blue-600"></i>Search Results
                    @if(request()->hasAny(['search', 'status', 'category', 'date_from', 'date_to']))
                        <span class="ml-2 text-sm text-gray-500">({{ $inquiries->total() }} found)</span>
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
                                    @if(auth()->guard('mcmc')->check())
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MCMC Status</th>
                                    @elseif(isset($userType) && $userType !== 'public')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                    @endif
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('Y-m-d') : 'N/A' }}</td>
                                        @if(auth()->guard('mcmc')->check())
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $mcmcStatus = $inquiry->mcmc_status ?? 'Pending Review';
                                                    $mcmcStatusColors = [
                                                        'Pending Review' => 'bg-yellow-100 text-yellow-800',
                                                        'Under Review' => 'bg-blue-100 text-blue-800',
                                                        'Approved' => 'bg-green-100 text-green-800',
                                                        'Rejected' => 'bg-red-100 text-red-800',
                                                        'Forwarded' => 'bg-purple-100 text-purple-800'
                                                    ];
                                                    $mcmcColorClass = $mcmcStatusColors[$mcmcStatus] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $mcmcColorClass }}">
                                                    {{ $mcmcStatus }}
                                                </span>
                                            </td>
                                        @elseif(isset($userType) && $userType !== 'public')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if(auth()->guard('mcmc')->check())
                                                    <!-- MCMC Actions -->
                                                    <a href="#" 
                                                       onclick="showInquiryDetails({{ $inquiry->I_ID }})"
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if(($inquiry->mcmc_status ?? 'Pending Review') == 'Pending Review')
                                                        <a href="{{ route('mcmc.inquiries.filter', $inquiry->I_ID) }}" 
                                                           class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                           title="Filter/Validate">
                                                            <i class="fas fa-gavel"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if(($inquiry->mcmc_status ?? '') == 'Approved')
                                                        <button onclick="quickAction({{ $inquiry->I_ID }}, 'forward')"
                                                                class="inline-flex items-center px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                                title="Forward to Agency">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <!-- Regular User Actions -->
                                                    <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(method_exists($inquiry, 'canBeEdited') && $inquiry->canBeEdited())
                                                        <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                           class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                @endif
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
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No results found</h3>
                        <p class="text-gray-600 mb-4">Try adjusting your search criteria or filters.</p>
                        <a href="{{ route('inquiries.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>Back to All Inquiries
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth()->guard('mcmc')->check())
<!-- Inquiry Details Modal - MCMC Only -->
<div id="inquiryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Inquiry Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="modalContent" class="mt-4 max-h-96 overflow-y-auto">
                <!-- Content will be loaded here -->
            </div>
            
            <!-- Modal Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-3 border-t">
                <button onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Close
                </button>
                <button id="filterButton" 
                        onclick="redirectToFilter()" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg hidden">
                    Filter/Validate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Decision Modal - MCMC Only -->
<div id="quickDecisionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Decision</h3>
            <form id="quickDecisionForm">
                @csrf
                <input type="hidden" id="quickInquiryId" name="inquiry_id">
                <input type="hidden" id="quickAction" name="action">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                    <div id="decisionOptions" class="space-y-2">
                        <!-- Decision options will be populated here -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="quickNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="quickNotes" name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Add any notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeQuickModal()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentInquiryId = null;

// Show inquiry details in modal
function showInquiryDetails(inquiryId) {
    currentInquiryId = inquiryId;
    
    // Show loading
    document.getElementById('modalContent').innerHTML = '<div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i></div>';
    document.getElementById('inquiryModal').classList.remove('hidden');
    
    // Fetch inquiry details via AJAX
    fetch(`/mcmc/inquiries/${inquiryId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayInquiryDetails(data.inquiry);
                
                // Show filter button if inquiry is pending
                if ((data.inquiry.mcmc_status || 'Pending Review') === 'Pending Review') {
                    document.getElementById('filterButton').classList.remove('hidden');
                } else {
                    document.getElementById('filterButton').classList.add('hidden');
                }
            } else {
                document.getElementById('modalContent').innerHTML = '<div class="text-red-600">Error loading inquiry details.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = '<div class="text-red-600">Error loading inquiry details.</div>';
        });
}

// Display inquiry details in modal
function displayInquiryDetails(inquiry) {
    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const content = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ID</label>
                    <div class="text-sm text-gray-900 font-mono">${inquiry.I_ID}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        ${inquiry.I_Status}
                    </span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <div class="text-sm text-gray-900">${inquiry.I_Title}</div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <div class="text-sm text-gray-900">${inquiry.I_Category}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date Submitted</label>
                    <div class="text-sm text-gray-900">${formatDate(inquiry.I_Date)}</div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded border max-h-32 overflow-y-auto">${inquiry.I_Description}</div>
            </div>
            
            ${inquiry.public_user ? `
                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Submitted By</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="text-gray-900">${inquiry.public_user.PU_Name || 'N/A'}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="text-gray-900">${inquiry.public_user.PU_Email || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            ` : ''}
            
            ${inquiry.I_filename ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supporting Evidence</label>
                    <a href="/storage/${inquiry.I_filename}" target="_blank" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-paperclip mr-1"></i>View Attachment
                    </a>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
}

// Close modal
function closeModal() {
    document.getElementById('inquiryModal').classList.add('hidden');
    currentInquiryId = null;
}

// Redirect to filter page
function redirectToFilter() {
    if (currentInquiryId) {
        window.location.href = `/mcmc/inquiries/${currentInquiryId}/filter`;
    }
}

// Quick action
function quickAction(inquiryId, action) {
    document.getElementById('quickInquiryId').value = inquiryId;
    document.getElementById('quickAction').value = action;
    
    // Set up decision options based on action
    const decisionOptions = document.getElementById('decisionOptions');
    if (action === 'forward') {
        decisionOptions.innerHTML = `
            <div class="text-sm text-gray-700">Forward this approved inquiry to the relevant agency?</div>
        `;
    }
    
    document.getElementById('quickDecisionModal').classList.remove('hidden');
}

// Close quick modal
function closeQuickModal() {
    document.getElementById('quickDecisionModal').classList.add('hidden');
}

// Handle quick decision form submission
document.getElementById('quickDecisionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const inquiryId = formData.get('inquiry_id');
    const action = formData.get('action');
    
    fetch(`/mcmc/inquiries/${inquiryId}/quick-action`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeQuickModal();
            location.reload(); // Refresh the page to show updated status
        } else {
            alert('Error processing request: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    });
});

// Close modal when clicking outside
document.getElementById('inquiryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('quickDecisionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickModal();
    }
});
</script>
@endif

@endsection