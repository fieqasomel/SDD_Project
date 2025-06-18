<<<<<<< HEAD
<div class="flex flex-col h-screen bg-gray-800 text-white w-64 md:w-1/4 lg:w-1/5 sidebar">
    <div class="flex items-center justify-between h-16 border-b border-gray-700">
        <h1 class="text-lg font-bold px-4">Agency Portal</h1>
        <button class="md:hidden p-2" id="toggleSidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto">
        <ul>
            <li class="hover:bg-gray-700">
                <a href="{{ route('agency.dashboard') }}" class="flex items-center py-2 px-4">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-clipboard-list mr-3"></i>Assigned Inquiries
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-clock mr-3"></i>Pending Inquiries
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-check-circle mr-3"></i>Completed Inquiries
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-chart-bar mr-3"></i>Performance Metrics
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-building mr-3"></i>Agency Profile
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="#" class="flex items-center py-2 px-4">
                    <i class="fas fa-bell mr-3"></i>Notifications
                </a>
            </li>
            <li class="hover:bg-gray-700 mt-auto border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center py-2 px-4 w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
=======
<div class="w-full bg-red-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto">
        <!-- Mobile header -->
        <div class="flex items-center justify-between px-4 h-16 border-b border-red-700 md:hidden">
            <h1 class="text-lg font-bold flex items-center">
                <i class="fas fa-user-circle mr-2"></i>Agency Portal
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
                <a href="{{ route('agency.dashboard') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tachometer-alt mr-2 group-hover:text-pink-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('inquiries.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-inbox mr-2 group-hover:text-pink-300"></i>
                    <span>View Inquiries</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tasks mr-2 group-hover:text-pink-300"></i>
                    <span>My Assignments</span>
                </a>
                <a href="{{ route('inquiries.report') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-pink-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-chart-bar mr-2 group-hover:text-pink-300"></i>
                    <span>Reports</span>
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
    
    // Toggle user menu
    function toggleUserMenu() {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('hidden');
    }
    
    // Close user menu when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.getElementById('userMenu');
        const userButton = event.target.closest('button');
        
        if (!userButton || !userButton.onclick) {
            userMenu.classList.add('hidden');
        }
    });
</script>
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
