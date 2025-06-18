<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inquiry History') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-history mr-3 text-blue-600"></i>Inquiry History
                    </h1>
                    <p class="text-gray-600">
                        {{ $userType === 'public' ? 'View all your previously submitted inquiries and track their progress' : 'View inquiry submission history and status updates' }}
                    </p>
                </div>
                <div class="flex space-x-3 mt-4 sm:mt-0">
                    <a href="{{ route('inquiries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-list mr-2"></i>Current Inquiries
                    </a>
                    @if($userType === 'public')
                        <a href="{{ route('inquiries.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>New Inquiry
                        </a>
                    @endif
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Inquiries -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-clipboard-list text-xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clock text-xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-400 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">In Progress</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-spinner text-xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Resolved -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Resolved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['resolved'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle text-xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Closed -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-gray-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Closed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['closed'] }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 rounded-full">
                            <i class="fas fa-archive text-xl text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>Filter History
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('inquiries.history') }}" class="space-y-4">
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
                                    <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="Validated" {{ request('status') === 'Validated' ? 'selected' : '' }}>Validated</option>
                                    <option value="Assigned" {{ request('status') === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Non-Serious" {{ request('status') === 'Non-Serious' ? 'selected' : '' }}>Non-Serious</option>
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
                                    <i class="fas fa-search mr-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Inquiry History List -->
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history mr-2 text-blue-600"></i>Inquiry History
                        <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $inquiries->total() }} Records</span>
                    </h3>
                </div>
                <div class="p-6">
                    @if($inquiries->count() > 0)
                        <!-- Desktop View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiry</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                        @if($userType !== 'public')
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($inquiries as $inquiry)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</div>
                                                    <div class="text-sm text-gray-600 max-w-xs">{{ Str::limit($inquiry->I_Title, 40) }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $inquiry->I_Category }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                                        'Under Review' => 'bg-blue-100 text-blue-800',
                                                        'Validated' => 'bg-teal-100 text-teal-800',
                                                        'Assigned' => 'bg-purple-100 text-purple-800',
                                                        'In Progress' => 'bg-indigo-100 text-indigo-800',
                                                        'Resolved' => 'bg-green-100 text-green-800',
                                                        'Closed' => 'bg-gray-100 text-gray-800',
                                                        'Rejected' => 'bg-red-100 text-red-800',
                                                        'Non-Serious' => 'bg-orange-100 text-orange-800'
                                                    ];
                                                    $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                                    {{ $inquiry->I_Status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col space-y-1">
                                                    @if($inquiry->progress->count() > 0)
                                                        <div class="text-xs text-green-600 font-medium">
                                                            <i class="fas fa-check-circle mr-1"></i>{{ $inquiry->progress->count() }} Update(s)
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            Last: {{ $inquiry->progress->last()->created_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    @else
                                                        <div class="text-xs text-gray-400">
                                                            <i class="fas fa-minus-circle mr-1"></i>No updates
                                                        </div>
                                                    @endif
                                                    @if($inquiry->isAssigned())
                                                        <div class="text-xs text-blue-600">
                                                            <i class="fas fa-user-check mr-1"></i>Assigned to {{ $inquiry->getAssignedAgency()->A_Name ?? 'Agency' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm text-gray-900">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('d/m/Y') : 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->diffForHumans() : '' }}</div>
                                                </div>
                                            </td>
                                            @if($userType !== 'public')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                       title="View Details">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                                    
                                                    @if($inquiry->canBeEdited())
                                                        @if($userType === 'public' && $inquiry->PU_ID === Auth::user()->PU_ID)
                                                            <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                               class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                               title="Edit">
                                                                <i class="fas fa-edit mr-1"></i>Edit
                                                            </a>
                                                        @elseif($userType !== 'public')
                                                            <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                               class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                               title="Edit">
                                                                <i class="fas fa-edit mr-1"></i>Edit
                                                            </a>
                                                        @endif
                                                    @endif
                                                    
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
                                                    
                                                    @if($inquiry->canBeDeleted())
                                                        @if($userType === 'public' && $inquiry->PU_ID === Auth::user()->PU_ID)
                                                            <a href="{{ route('inquiries.delete', $inquiry->I_ID) }}" 
                                                               class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                               title="Delete">
                                                                <i class="fas fa-trash mr-1"></i>Delete
                                                            </a>
                                                        @elseif($userType === 'mcmc')
                                                            <a href="{{ route('inquiries.delete', $inquiry->I_ID) }}" 
                                                               class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                               title="Delete">
                                                                <i class="fas fa-trash mr-1"></i>Delete
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

                        <!-- Mobile View -->
                        <div class="md:hidden space-y-4">
                            @foreach($inquiries as $inquiry)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $inquiry->I_ID }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($inquiry->I_Title, 50) }}</p>
                                        </div>
                                        @php
                                            $statusColors = [
                                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                                'Under Review' => 'bg-blue-100 text-blue-800',
                                                'Validated' => 'bg-teal-100 text-teal-800',
                                                'Assigned' => 'bg-purple-100 text-purple-800',
                                                'In Progress' => 'bg-indigo-100 text-indigo-800',
                                                'Resolved' => 'bg-green-100 text-green-800',
                                                'Closed' => 'bg-gray-100 text-gray-800',
                                                'Rejected' => 'bg-red-100 text-red-800',
                                                'Non-Serious' => 'bg-orange-100 text-orange-800'
                                            ];
                                            $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                            {{ $inquiry->I_Status }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Category:</span>
                                            <div class="font-medium">{{ $inquiry->I_Category }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Submitted:</span>
                                            <div class="font-medium">{{ $inquiry->I_Date ? $inquiry->I_Date->format('d/m/Y') : 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Progress:</span>
                                            <div class="font-medium">
                                                @if($inquiry->progress->count() > 0)
                                                    <span class="text-green-600">{{ $inquiry->progress->count() }} Update(s)</span>
                                                @else
                                                    <span class="text-gray-400">No updates</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Assignment:</span>
                                            <div class="font-medium">
                                                @if($inquiry->isAssigned())
                                                    <span class="text-blue-600">Assigned</span>
                                                @else
                                                    <span class="text-gray-400">Not assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                <i class="fas fa-eye mr-2"></i>View Details
                                            </a>
                                            
                                            @if($inquiry->canBeEdited())
                                                @if($userType === 'public' && $inquiry->PU_ID === Auth::user()->PU_ID)
                                                    <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-edit mr-2"></i>Edit
                                                    </a>
                                                @elseif($userType !== 'public')
                                                    <a href="{{ route('inquiries.edit', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-edit mr-2"></i>Edit
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            @if($userType === 'mcmc')
                                                @if(!$inquiry->isAssigned())
                                                    <a href="{{ route('assignments.assign', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-user-plus mr-2"></i>Assign
                                                    </a>
                                                @else
                                                    <a href="{{ route('assignments.view', $inquiry->complaint->C_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-tasks mr-2"></i>Assignment
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            @if($inquiry->canBeDeleted())
                                                @if($userType === 'public' && $inquiry->PU_ID === Auth::user()->PU_ID)
                                                    <a href="{{ route('inquiries.delete', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </a>
                                                @elseif($userType === 'mcmc')
                                                    <a href="{{ route('inquiries.delete', $inquiry->I_ID) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6 flex justify-center">
                            {{ $inquiries->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <i class="fas fa-inbox text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inquiry History Found</h3>
                            <p class="text-gray-600 mb-6">
                                @if(request()->hasAny(['search', 'status', 'category', 'date_from', 'date_to']))
                                    No inquiries found matching your search criteria. Try adjusting your filters.
                                @else
                                    {{ $userType === 'public' ? "You haven't submitted any inquiries yet." : "No inquiries found in the system." }}
                                @endif
                            </p>
                            @if($userType === 'public')
                                <a href="{{ route('inquiries.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <i class="fas fa-plus mr-2"></i>Submit Your First Inquiry
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>