@php
    $user = Auth::guard('agency')->user();
    $userName = $user->A_Name ?? 'Agency User';
@endphp

<div x-data="{ sidebarOpen: false }">
    <!-- Mobile menu button -->
    <div class="lg:hidden">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="fixed top-4 left-4 z-50 p-2 rounded-md bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      :d="sidebarOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'">
                </path>
            </svg>
        </button>
    </div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
         class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 lg:w-64">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-center h-16 px-4 bg-green-600">
            <h2 class="text-xl font-semibold text-white">Agency Portal</h2>
        </div>

        <!-- User Info -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">{{ substr($userName, 0, 2) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $userName }}</p>
                    <p class="text-xs text-gray-500">Agency</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-4 px-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('agency.dashboard') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('agency.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <!-- Inquiry Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inquiry Management</h3>
                
                <a href="{{ route('agency.inquiries.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('agency.inquiries.index') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Active Inquiries
                </a>

                <a href="{{ route('agency.inquiries.history') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('agency.inquiries.history') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Inquiry History
                </a>
            </div>

            <!-- Assignment Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assignment Management</h3>
                
                <a href="{{ route('assignments.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('assignments.index') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    My Assignments
                </a>

                <a href="{{ route('assignments.report') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('assignments.report') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Assignment Reports
                </a>
            </div>

            <!-- Search & Reports -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Search & Reports</h3>
                
                <a href="{{ route('inquiries.search') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.search') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search Inquiries
                </a>

                <a href="{{ route('inquiries.report') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.report') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Generate Reports
                </a>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Agency Profile
            </a>

            <!-- Help & Support -->
            <a href="#" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.5a9.5 9.5 0 110 19.5 9.5 9.5 0 010-19z"></path>
                </svg>
                Help & Support
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="absolute bottom-4 left-4 right-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    </div>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-black opacity-50 lg:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-50"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-50"
         x-transition:leave-end="opacity-0">
    </div>
</div>