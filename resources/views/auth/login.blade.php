<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl mx-auto" x-data="{ selectedUserType: '{{ old('user_type', 'public_user') }}' }" x-init="console.log('Alpine initialized with user type:', selectedUserType)">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-sign-in-alt text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Login to SDD System</h2>
                    <p class="text-lg opacity-90">Select your user type and enter your credentials</p>
                </div>
=======
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

<<<<<<< HEAD
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                            <span class="font-semibold">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside ml-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- User Type Selection -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Select User Type</label>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="user-type-card border-2 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 hover:shadow-lg"
                                 :class="selectedUserType === 'public_user' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'"
                                 @click="selectedUserType = 'public_user'">
                                <div class="text-3xl text-blue-600 mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">Public User</h6>
                                <small class="text-gray-600">General Users</small>
                            </div>
                            <div class="user-type-card border-2 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 hover:shadow-lg"
                                 :class="selectedUserType === 'agency' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300'"
                                 @click="selectedUserType = 'agency'">
                                <div class="text-3xl text-green-600 mb-3">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">Agency</h6>
                                <small class="text-gray-600">Government Agencies</small>
                            </div>
                            <div class="user-type-card border-2 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 hover:shadow-lg"
                                 :class="selectedUserType === 'mcmc' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300'"
                                 @click="selectedUserType = 'mcmc'">
                                <div class="text-3xl text-red-600 mb-3">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">MCMC</h6>
                                <small class="text-gray-600">MCMC Staff</small>
                            </div>
                        </div>
                        <input type="hidden" name="user_type" :value="selectedUserType">
                    </div>

                    <!-- Email/Username field that changes based on user type -->
                    <div class="mb-6" x-show="selectedUserType === 'public_user'" x-transition>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email address"
                               x-bind:required="selectedUserType === 'public_user'">
                    </div>
                    
                    <!-- Username field for Agency and MCMC -->
                    <div class="mb-6" x-show="selectedUserType === 'agency' || selectedUserType === 'mcmc'" x-transition>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-500"></i>Username
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}" 
                               placeholder="Enter your username"
                               x-bind:required="selectedUserType === 'agency' || selectedUserType === 'mcmc'">
                    </div>

                    <!-- Password -->
                    <div class="mb-8">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                        </label>
                        <input type="password" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                    </div>

                    <!-- Login Button -->
                    <div class="mb-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-4 px-6 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-lg">
                            <i class="fas fa-sign-in-alt mr-3"></i>Login to Account
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-600">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-purple-600 font-semibold transition-colors duration-200">
                                Register here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="text-white hover:text-blue-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>
    </div>

    <!-- Fallback JavaScript for user type functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeInput = document.querySelector('input[name="user_type"]');
            const emailField = document.querySelector('#email').closest('div');
            const usernameField = document.querySelector('#username').closest('div');
            const userTypeCards = document.querySelectorAll('.user-type-card');
            
            let selectedUserType = userTypeInput.value || 'public_user';
            
            function updateFields(userType) {
                // Update hidden input
                userTypeInput.value = userType;
                
                // Show/hide appropriate fields
                if (userType === 'public_user') {
                    emailField.style.display = 'block';
                    usernameField.style.display = 'none';
                    document.querySelector('#email').required = true;
                    document.querySelector('#username').required = false;
                } else {
                    emailField.style.display = 'none';
                    usernameField.style.display = 'block';
                    document.querySelector('#email').required = false;
                    document.querySelector('#username').required = true;
                }
                
                // Update card styling
                userTypeCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
                    card.classList.add('border-gray-200');
                });
                
                // Add active styling to selected card
                const selectedCard = Array.from(userTypeCards).find(card => {
                    const cardText = card.querySelector('h6').textContent.toLowerCase().replace(' ', '_');
                    return cardText === userType;
                });
                
                if (selectedCard) {
                    selectedCard.classList.remove('border-gray-200');
                    if (userType === 'public_user') {
                        selectedCard.classList.add('border-blue-500', 'bg-blue-50');
                    } else if (userType === 'agency') {
                        selectedCard.classList.add('border-green-500', 'bg-green-50');
                    } else if (userType === 'mcmc') {
                        selectedCard.classList.add('border-red-500', 'bg-red-50');
                    }
                }
            }
            
            // Initialize with default user type
            updateFields(selectedUserType);
            
            // Add click handlers to user type cards
            userTypeCards.forEach(card => {
                card.addEventListener('click', function() {
                    const cardType = this.querySelector('h6').textContent.toLowerCase().replace(' ', '_');
                    selectedUserType = cardType;
                    updateFields(cardType);
                });
            });
        });
    </script>
</body>
</html>
=======
                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
