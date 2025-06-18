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