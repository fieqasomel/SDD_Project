<<<<<<< HEAD
<div class="w-full bg-red-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto">
        <!-- Mobile header -->
        <div class="flex items-center justify-between px-4 h-16 border-b border-red-700 md:hidden">
            <h1 class="text-lg font-bold flex items-center">
                <i class="fas fa-user-circle mr-2"></i>Public User Portal
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
                <a href="{{ route('publicuser.dashboard') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tachometer-alt mr-2 group-hover:text-pink-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('inquiries.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-clipboard-list mr-2 group-hover:text-pink-300"></i>
                    <span>My Inquiries</span>
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
    $user = Auth::guard('publicuser')->user();
    $userName = $user->PU_Name ?? 'Public User';
@endphp

<!-- Desktop Sidebar - Visible only on large screens -->
<div class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0">
    <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-0 overflow-y-auto">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
            <h2 class="text-xl font-semibold text-white">Public Portal</h2>
        </div>

        <!-- User Info -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">{{ substr($userName, 0, 2) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $userName }}</p>
                    <p class="text-xs text-gray-500">Public User</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-4 px-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('publicuser.dashboard') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('publicuser.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <!-- My Inquiries -->
            <a href="{{ route('inquiries.index') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                My Inquiries
            </a>

            <!-- Submit New Inquiry -->
            <a href="{{ route('inquiries.create') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.create') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Submit Inquiry
            </a>

            <!-- Search Inquiries -->
            <a href="{{ route('inquiries.search') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.search') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Search Inquiries
            </a>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                My Profile
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    </div>
</div>

<!-- Mobile Sidebar -->
<div x-data="{ sidebarOpen: false }" class="lg:hidden">
    <!-- Mobile menu button -->
    <button @click="sidebarOpen = !sidebarOpen" 
            class="fixed top-4 left-4 z-50 p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  :d="sidebarOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'">
            </path>
        </svg>
    </button>

    <!-- Mobile Sidebar Panel -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 flex lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        
        <!-- Sidebar panel -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false" 
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
                    <h2 class="text-xl font-semibold text-white">Public Portal</h2>
                </div>

                <!-- User Info -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ substr($userName, 0, 2) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $userName }}</p>
                            <p class="text-xs text-gray-500">Public User</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Links -->
                <nav class="mt-4 px-4 space-y-2">
                    <a href="{{ route('publicuser.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('publicuser.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('inquiries.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        My Inquiries
                    </a>
                    <a href="{{ route('inquiries.create') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inquiries.create') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Submit Inquiry
                    </a>
                </nav>
            </div>
            
            <!-- Mobile Logout -->
            <div class="p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
>>>>>>> 847bd712ee5c51c00a5362abdefcc7e763f5e46a
