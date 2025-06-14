@php
    // Check which guard the user is authenticated with
    $user = null;
    $currentGuard = null;
    $userName = 'User';
    $userEmail = 'No email';
    
    if (Auth::guard('mcmc')->check()) {
        $user = Auth::guard('mcmc')->user();
        $currentGuard = 'mcmc';
        $userName = $user->M_Name ?? 'MCMC User';
        $userEmail = $user->M_Email ?? 'No email';
    } elseif (Auth::guard('agency')->check()) {
        $user = Auth::guard('agency')->user();
        $currentGuard = 'agency';
        $userName = $user->A_Name ?? 'Agency User';
        $userEmail = $user->A_Email ?? 'No email';
    } elseif (Auth::guard('publicuser')->check()) {
        $user = Auth::guard('publicuser')->user();
        $currentGuard = 'publicuser';
        $userName = $user->PU_Name ?? 'Public User';
        $userEmail = $user->PU_Email ?? 'No email';
    } elseif (Auth::check()) {
        $user = Auth::user();
        $currentGuard = 'web';
        $userName = $user->name ?? 'User';
        $userEmail = $user->email ?? 'No email';
    }
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if($currentGuard === 'agency')
                        <x-nav-link href="{{ route('agency.inquiries.index') }}" :active="request()->routeIs('agency.inquiries.*')">
                            {{ __('My Inquiries') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('agency.inquiries.history') }}" :active="request()->routeIs('agency.inquiries.history')">
                            {{ __('Inquiry History') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ $user?->currentTeam?->name ?? 'Default Team' }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    @if($user?->currentTeam)
                                        <x-dropdown-link href="{{ route('teams.show', $user->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>
                                    @endif

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if ($user && method_exists($user, 'allTeams') && $user->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach ($user->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ $user?->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $userName }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ $userName }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Agency Information -->
                            @if($currentGuard === 'agency')
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Agency Information') }}
                                </div>
                                <x-dropdown-link href="{{ route('agency.dashboard') }}">
                                    {{ __('Agency Details') }}
                                </x-dropdown-link>
                            @endif

                            <!-- MCMC Information -->
                            @if($currentGuard === 'mcmc' || $currentGuard === 'agency')
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('MCMC Information') }}
                                </div>
                                <x-dropdown-link href="{{ $currentGuard === 'mcmc' ? route('mcmc.dashboard') : '#' }}" onclick="if(this.href === '#') alert('MCMC Information - Coming Soon')">
                                    {{ __('MCMC Details') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if($currentGuard === 'agency')
                <x-responsive-nav-link href="{{ route('agency.inquiries.index') }}" :active="request()->routeIs('agency.inquiries.*')">
                    {{ __('My Inquiries') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('agency.inquiries.history') }}" :active="request()->routeIs('agency.inquiries.history')">
                    {{ __('Inquiry History') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $userName }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ $userName }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $userEmail }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Agency Information -->
                @if($currentGuard === 'agency')
                    <x-responsive-nav-link href="{{ route('agency.dashboard') }}">
                        {{ __('Agency Details') }}
                    </x-responsive-nav-link>
                @endif

                <!-- MCMC Information -->
                @if($currentGuard === 'mcmc' || $currentGuard === 'agency')
                    <x-responsive-nav-link href="{{ $currentGuard === 'mcmc' ? route('mcmc.dashboard') : '#' }}" onclick="if(this.href === '#') alert('MCMC Information - Coming Soon')">
                        {{ __('MCMC Details') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    @if($user?->currentTeam)
                        <x-responsive-nav-link href="{{ route('teams.show', $user->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                            {{ __('Team Settings') }}
                        </x-responsive-nav-link>
                    @endif

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if ($user && method_exists($user, 'allTeams') && $user->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach ($user->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
