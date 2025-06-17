<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MySebenarnya')</title>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @yield('styles')
        .merriweather-font {
            font-family: 'Merriweather', serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-20">
                <div class="flex items-center flex-grow">
                    <img src="{{ asset('images/logoMalaysia.png') }}" alt="logo malaysia"
                    class="mr-4" style="width: 100px; height:70px;">
                    <img src="{{ asset('images/logoMCMC.png') }}" alt="logo malaysia"
                    class="mr-2" style="width: 100px; height:70px;">
                    <a href="#" class="flex flex-col items-left text-black font-bold text-lg">
                        <span class="italic merriweather-font">MySebenarnya</span>
                        <span class="text-sm merriweather-font">Tak Pasti Jangan Kongsi</span>
                    </a>
                </div>
                
                <!-- Right side navigation (moved slightly to the left) -->
                <div class="flex items-center space-x-4 mr-8">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-[#09143C] ]hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
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
                        <a href="{{ route('login') }}" class="text-[#09143C] hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="text-[#09143C] hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button x-data="{ open: false }" @click="open = !open" class="text-[#09143C] hover:text-blue-200 p-2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen flex flex-col">
        @auth
            @if(Auth::user() instanceof App\Models\PublicUser)
                @include('Navbar.PublicUserNavbar')
            @elseif(Auth::user() instanceof App\Models\Agency)
                @include('Navbar.AgencyNavBar')
            @elseif(Auth::user() instanceof App\Models\MCMC)
                @include('Navbar.MCMCNavBar')
            @endif
        @endauth
        
        <div class="flex-1 p-6">
            @yield('content')
        </div>
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