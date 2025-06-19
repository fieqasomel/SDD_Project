@extends('layouts.app')

@section('title', 'My Inquiries - MySebenarnya System')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                @if(isset($isMCMC) && $isMCMC)
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">All Inquiries</h1>
                    <p class="text-gray-600">View and manage all submitted inquiries from users</p>
                @elseif(Auth::user() instanceof \App\Models\Agency)
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Assigned Inquiries</h1>
                    <p class="text-gray-600">View and manage inquiries assigned to your agency with complete history tracking</p>
                @else
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Inquiries</h1>
                    <p class="text-gray-600">View and manage your inquiry submissions</p>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                @if(Auth::user() instanceof \App\Models\Agency)
                    <a href="{{ route('assignments.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-tasks mr-2"></i>Current Assignments
                    </a>
                    <a href="{{ route('assignments.report') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Report
                    </a>
                @elseif(isset($isMCMC) && $isMCMC)
                    <a href="{{ route('assignments.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-tasks mr-2"></i>Manage Assignments
                    </a>
                    <a href="{{ route('inquiries.report') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Reports
                    </a>
                @else
                    <a href="{{ route('inquiries.public') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-globe mr-2"></i>Public Inquiries
                    </a>
                    <a href="{{ route('inquiries.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>New Inquiry
                    </a>
                @endif
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Inquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Inquiries</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
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
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['resolved'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user() instanceof \App\Models\Agency)
        <!-- Additional Agency Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Verified True -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-emerald-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wide mb-1">Verified True</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['verified_true'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full">
                        <i class="fas fa-check-double text-2xl text-emerald-600"></i>
                    </div>
                </div>
            </div>

            <!-- Identified Fake -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-red-600 uppercase tracking-wide mb-1">Identified Fake</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['identified_fake'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-gray-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Rejected</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-full">
                        <i class="fas fa-ban text-2xl text-gray-600"></i>
                    </div>
                </div>
            </div>

            <!-- Closed -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-1">Closed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['closed'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-archive text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
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
                <form method="GET" action="{{ route('inquiries.index') }}" class="space-y-4">
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
                                <option value="General Information" {{ request('category') == 'General Information' ? 'selected' : '' }}>General Information</option>
                                <option value="Technical Support" {{ request('category') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="Billing" {{ request('category') == 'Billing' ? 'selected' : '' }}>Billing</option>
                                <option value="Complaint" {{ request('category') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                <option value="Service Request" {{ request('category') == 'Service Request' ? 'selected' : '' }}>Service Request</option>
                                <option value="Feedback" {{ request('category') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
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
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                @if(Auth::user() instanceof \App\Models\Agency)
                                    <option value="Verified as True" {{ request('status') == 'Verified as True' ? 'selected' : '' }}>Verified as True</option>
                                    <option value="Identified as Fake" {{ request('status') == 'Identified as Fake' ? 'selected' : '' }}>Identified as Fake</option>
                                @endif
                            </select>
                        </div>

                        @if(Auth::user() instanceof \App\Models\Agency)
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
                        @endif

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <div class="flex gap-2 w-full">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                    <i class="fas fa-search mr-1"></i>Search
                                </button>
                                <a href="{{ route('inquiries.index') }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm"
                                   title="Clear filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Active Filters -->
                @if(request()->hasAny(['search', 'category', 'status']))
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
                            
                            <a href="{{ route('inquiries.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                                <i class="fas fa-times mr-1"></i>Clear all
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-2 text-blue-600"></i>Inquiries List
                    @if(request()->hasAny(['search', 'category', 'status']))
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
                                    @if(Auth::user() instanceof \App\Models\Agency)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    @if(Auth::user() instanceof \App\Models\Agency)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                    @else
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $inquiry->I_Title }}</td>
                                        @if(Auth::user() instanceof \App\Models\Agency)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>
                                                    <div class="font-medium">{{ $inquiry->publicUser->PU_Name ?? 'N/A' }}</div>
                                                    <div class="text-gray-500">{{ $inquiry->publicUser->PU_Email ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                        @endif
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View Button -->
                                                <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </a>
                                                
                                                @if(isset($isPublicUser) && $isPublicUser)
                                                    <!-- Public User Actions -->
                                                    @if($inquiry->I_Status === 'Pending')
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                           class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                                                            <i class="fas fa-edit mr-1"></i>Edit
                                                        </a>
                                                        
                                                        <!-- Delete Button -->
                                                        <form method="POST" action="{{ route('inquiries.destroy', $inquiry->I_ID) }}" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    onclick="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')"
                                                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                                                                <i class="fas fa-trash mr-1"></i>Delete
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-gray-500 italic">Cannot edit/delete</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination - Only show if using paginator -->
                    @if(method_exists($inquiries, 'links'))
                    <div class="mt-6">
                        {{ $inquiries->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            @if(request()->hasAny(['search', 'category', 'status']))
                                <i class="fas fa-search text-5xl"></i>
                            @else
                                <i class="fas fa-inbox text-5xl"></i>
                            @endif
                        </div>
                        
                        @if(request()->hasAny(['search', 'category', 'status']))
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Results Found</h3>
                            <p class="text-gray-600 mb-4">No inquiries match your search criteria. Try adjusting your filters.</p>
                            <a href="{{ route('inquiries.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Clear Filters
                            </a>
                        @else
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inquiries Found</h3>
                            <p class="text-gray-600 mb-4">You don't have any inquiries yet. Start by creating your first inquiry!</p>
                            <a href="{{ route('inquiries.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>Create First Inquiry
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
});
</script>
@endpush

@endsection