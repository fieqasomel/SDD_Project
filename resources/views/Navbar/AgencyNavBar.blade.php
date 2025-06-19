<div class="w-full bg-teal-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto">
        <!-- Mobile header -->
        <div class="flex items-center justify-between px-4 h-16 border-b border-teal-700 md:hidden">
            <h1 class="text-lg font-bold flex items-center">
                <i class="fas fa-user-circle mr-2"></i>Agency Portal
            </h1>
            <button class="p-2 rounded hover:bg-teal-700 transition-colors" id="toggleSidebar">
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
                <a href="{{ route('agency.dashboard') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-lime-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tachometer-alt mr-2 group-hover:text-lime-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('inquiries.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-lime-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-clipboard-list mr-2 group-hover:text-lime-300"></i>
                    <span>My Inquiries</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-lime-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-tasks mr-2 group-hover:text-lime-300"></i>
                    <span>My Assignments</span>
                </a>
                <a href="{{ route('inquiries.search') }}" class="w-full md:w-auto flex items-center py-3 px-4 hover:bg-lime-900 rounded-md transition-colors duration-200 group">
                    <i class="fas fa-search mr-2 group-hover:text-lime-300"></i>
                    <span>My Tracker</span>
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