<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MySebenarnya')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @yield('styles')
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="flex items-center text-white font-bold text-lg">
                        <i class="fas fa-building mr-2"></i>MySebenarnya
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        @auth
                            <a href="{{ route('inquiries.index') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-clipboard-list mr-1"></i>Manage Inquiries
                            </a>
                            @if(Auth::user() instanceof App\Models\PublicUser)
                                <a href="{{ route('publicuser.dashboard') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                </a>
                            @elseif(Auth::user() instanceof App\Models\Agency)
                                <a href="{{ route('agency.dashboard') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                </a>
                            @elseif(Auth::user() instanceof App\Models\MCMC)
                                <a href="{{ route('mcmc.dashboard') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <!-- Right side navigation -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user mr-1"></i>
                                @if(Auth::user() instanceof App\Models\PublicUser)
                                    {{ Auth::user()->PU_Name }}
                                @elseif(Auth::user() instanceof App\Models\Agency)
                                    {{ Auth::user()->A_Name }}
                                @elseif(Auth::user() instanceof App\Models\MCMC)
                                    {{ Auth::user()->M_Name }}
                                @endif
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Profile
                                </a>
                                <hr class="border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button x-data="{ open: false }" @click="open = !open" class="text-white hover:text-blue-200 p-2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-600">&copy; {{ date('Y') }} MySebenarnya. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    @yield('scripts')
</body>
</html>