<<<<<<< HEAD
<div class="w-full bg-red-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto">
        <!-- Mobile header -->
        <div class="flex items-center justify-between px-4 h-16 border-b border-red-700 md:hidden">
            <h1 class="text-lg font-bold flex items-center">
                <i class="fas fa-user-circle mr-2"></i>MCMC Portal
            </h1>
            <button class="p-2 rounded hover:bg-red-700 transition-colors" id="toggleSidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Desktop & Mobile Navigation -->
        <nav class="hidden md:flex flex-col md:flex-row items-center justify-between px-4" id="navMenu">
            <div class="flex flex-col md:flex-row items-center md:space-x-1">
                <a href="{{ route('mcmc.dashboard') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tachometer-alt mr-2 group-hover:text-pink-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('inquiries.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-clipboard-list mr-2 group-hover:text-pink-300"></i>
                    <span>My Inquiries</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-clipboard-list mr-2 group-hover:text-pink-300"></i>
                    <span>Assignments Management</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-clipboard-list mr-2 group-hover:text-pink-300"></i>
                    <span>Report</span>
                </a>
            </div>
        </nav>
    </div>
</div>

<!-- Add JavaScript for mobile menu toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleSidebar');
        const navMenu = document.getElementById('navMenu');
        
        if (toggleButton && navMenu) {
            toggleButton.addEventListener('click', function() {
                navMenu.classList.toggle('hidden');
            });
            
            // Show menu by default on larger screens
            function checkScreenSize() {
                if (window.innerWidth >= 768) { // md breakpoint
                    navMenu.classList.remove('hidden');
                } else {
                    navMenu.classList.add('hidden');
                }
            }
            
            // Initial check
            checkScreenSize();
            
            // Check on resize
            window.addEventListener('resize', checkScreenSize);
        }
    });
</script>
=======
@php
    $user = Auth::guard('mcmc')->user();
    $userName = $user->M_Name ?? 'MCMC User';
@endphp

<div x-data="{ sidebarOpen: false }">
    <!-- Mobile menu button -->
    <div class="lg:hidden">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="fixed top-4 left-4 z-50 p-2 rounded-md bg-purple-600 text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
        <div class="flex items-center justify-center h-16 px-4 bg-purple-600">
            <h2 class="text-xl font-semibold text-white">MCMC Admin</h2>
        </div>

        <!-- User Info -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">{{ substr($userName, 0, 2) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $userName }}</p>
                    <p class="text-xs text-gray-500">MCMC Administrator</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-4 px-4 space-y-2 overflow-y-auto max-h-[calc(100vh-300px)]">
            <!-- Dashboard -->
            <a href="{{ route('mcmc.dashboard') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <!-- Agency Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Agency Management</h3>
                
                <a href="{{ route('mcmc.agencies.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.agencies.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Manage Agencies
                </a>

                <a href="{{ route('mcmc.agencies.create') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.agencies.create') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Agency
                </a>
            </div>

            <!-- Inquiry Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inquiry Management</h3>
                
                <a href="{{ route('mcmc.inquiries.new') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.inquiries.new') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    New Inquiries
                </a>

                <a href="{{ route('mcmc.inquiries.processed') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.inquiries.processed') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Processed Inquiries
                </a>
            </div>

            <!-- Assignment Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assignment Management</h3>
                
                <a href="{{ route('assignments.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('assignments.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    All Assignments
                </a>

                <a href="{{ route('assignments.rejected') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('assignments.rejected') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Rejected Assignments
                </a>
            </div>

            <!-- User Management -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</h3>
                
                <a href="{{ route('mcmc.users.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.users.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    View All Users
                </a>
            </div>

            <!-- Reports & Analytics -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports & Analytics</h3>
                
                <a href="{{ route('mcmc.reports.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.reports.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    User Reports
                </a>

                <a href="{{ route('mcmc.inquiry-reports.generate') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.inquiry-reports.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Inquiry Reports
                </a>

                <a href="{{ route('assignments.report') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('assignments.report') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Assignment Reports
                </a>
            </div>

            <!-- Activity Logs -->
            <div class="space-y-1">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System Monitoring</h3>
                
                <a href="{{ route('mcmc.activity.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.activity.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Activity Logs
                </a>

                <a href="{{ route('mcmc.inquiry-activity.index') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('mcmc.inquiry-activity.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Inquiry Activity
                </a>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                MCMC Profile
            </a>

            <!-- System Settings -->
            <a href="#" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                System Settings
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
>>>>>>> 847bd712ee5c51c00a5362abdefcc7e763f5e46a
