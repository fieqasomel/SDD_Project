<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Inquiries') }}
        </h2>
    </x-slot>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Inquiries</h1>
                <p class="text-gray-600">Find specific inquiries using advanced search filters</p>
            </div>
            <a href="{{ route('inquiries.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 mt-4 sm:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
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

        <!-- MCMC Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-green-600"></i>MCMC Filter & Validation
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('inquiries.search') }}" class="space-y-4">
                    <!-- Preserve existing search parameters -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('date_from'))
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    @endif
                    @if(request('date_to'))
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Priority Filter -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Priority Level
                            </label>
                            <select id="priority" 
                                    name="priority"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                <option value="">All Priorities</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                            </select>
                        </div>

                        <!-- Validation Status -->
                        <div>
                            <label for="validation_status" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-check-circle mr-1"></i>Validation Status
                            </label>
                            <select id="validation_status" 
                                    name="validation_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('validation_status') == 'pending' ? 'selected' : '' }}>Pending Validation</option>
                                <option value="validated" {{ request('validation_status') == 'validated' ? 'selected' : '' }}>Validated</option>
                                <option value="rejected" {{ request('validation_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Attachment Filter -->
                        <div>
                            <label for="has_attachment" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-paperclip mr-1"></i>Attachment
                            </label>
                            <select id="has_attachment" 
                                    name="has_attachment"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                <option value="">All Inquiries</option>
                                <option value="yes" {{ request('has_attachment') == 'yes' ? 'selected' : '' }}>With Attachment</option>
                                <option value="no" {{ request('has_attachment') == 'no' ? 'selected' : '' }}>Without Attachment</option>
                            </select>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-end">
                            <div class="flex gap-2 w-full">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                    <i class="fas fa-filter mr-1"></i>Apply Filter
                                </button>
                                <a href="{{ route('inquiries.search') }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm"
                                   title="Clear MCMC filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('Y-m-d') : 'N/A' }}</td>
                                        @if($userType !== 'public')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($inquiry->canBeEdited())
                                                    <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
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
</x-app-layout>