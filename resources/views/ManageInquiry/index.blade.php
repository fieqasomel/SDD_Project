<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Inquiries') }}
        </h2>
    </x-slot>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Inquiries</h1>
                <p class="text-gray-600">Track and manage all inquiry submissions</p>
            </div>
            @if($userType === 'public')
                <a href="{{ route('inquiries.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 mt-4 sm:mt-0">
                    <i class="fas fa-plus mr-2"></i>Submit New Inquiry
                </a>
            @endif
        </div>

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

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-search mr-2 text-blue-600"></i>Search & Filter
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('inquiries.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by title or description..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">All Statuses</option>
                                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                            <select id="category" name="category" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Inquiry::CATEGORIES as $category)
                                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-2">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-2">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4 sm:mb-0">
                    <i class="fas fa-list mr-2 text-blue-600"></i>Inquiries List
                </h3>
                @if($userType !== 'public')
                    <a href="{{ route('inquiries.report') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Report
                    </a>
                @endif
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
                                    @if($userType !== 'public')
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Date ? $inquiry->I_Date->format('Y-m-d') : 'N/A' }}</td>
                                        @if($userType !== 'public')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                   title="View">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </a>
                                                
                                                @if($userType === 'mcmc')
                                                    @if(!$inquiry->isAssigned())
                                                        <a href="{{ route('assignments.assign', $inquiry->I_ID) }}" 
                                                           class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                           title="Assign to Agency">
                                                            <i class="fas fa-user-plus mr-1"></i>Assign
                                                        </a>
                                                    @else
                                                        <a href="{{ route('assignments.view', $inquiry->complaint->C_ID) }}" 
                                                               class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                           title="View Assignment">
                                                            <i class="fas fa-tasks mr-1"></i>Assignment
                                                        </a>
                                                    @endif
                                                @endif
                                                
                                                @if($inquiry->canBeEdited())
                                                    <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                       title="Edit">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                @endif
                                                @if($inquiry->canBeDeleted() && ($userType === 'public' || $userType === 'mcmc'))
                                                    <form method="POST" action="{{ route('inquiries.destroy', $inquiry->I_ID) }}" 
                                                          class="inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                                title="Delete">
                                                            <i class="fas fa-trash mr-1"></i>Delete
                                                        </button>
                                                    </form>
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
                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No inquiries found</h3>
                        <p class="text-gray-600 mb-4">
                            @if($userType === 'public')
                                You haven't submitted any inquiries yet.
                            @else
                                No inquiries match your current filters.
                            @endif
                        </p>
                        @if($userType === 'public')
                            <a href="{{ route('inquiries.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>Submit your first inquiry
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-4 text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-4 text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif
</x-app-layout>