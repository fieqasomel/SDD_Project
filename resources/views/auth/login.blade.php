<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MySebenarnya System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            left: 80%;
            animation-delay: 5s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 70%;
            animation-delay: 10s;
        }
        
        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 20%;
            animation-delay: 15s;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-100px) rotate(180deg);
                opacity: 0.3;
            }
            100% {
                transform: translateY(0px) rotate(360deg);
                opacity: 0.7;
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .user-type-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .user-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .login-button {
            position: relative;
            overflow: hidden;
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .login-button:hover::before {
            left: 100%;
        }
        
        /* Ensure smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        body {
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .glass-effect {
                margin: 1rem;
                border-radius: 1.5rem;
            }
            
            .user-type-card {
                padding: 1rem;
            }
            
            .form-input {
                padding: 0.75rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 relative">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="min-h-screen py-8 px-4 relative z-10">
        <div class="w-full max-w-4xl mx-auto" x-data="{ selectedUserType: '{{ old('user_type', 'public_user') }}' }">
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white px-8 py-12 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6 backdrop-blur-sm shadow-lg">
                            <i class="fas fa-shield-halved text-3xl"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-3">Welcome Back</h1>
                        <p class="text-xl opacity-90 mb-2">MySebenarnya System</p>
                        <p class="text-sm opacity-75">Secure • Reliable • Trusted</p>
                    </div>
                </div>

                <!-- Login Form Section -->
                <div class="p-8 md:p-12 bg-white">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-6 py-4 rounded-lg mb-8 shadow-sm">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                                <span class="font-semibold text-lg">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc list-inside ml-6 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @session('status')
                        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-6 py-4 rounded-lg mb-8 shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                                <span class="font-medium">{{ $value }}</span>
                            </div>
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-8">
                        @csrf
                        
                        <!-- User Type Selection -->
                        <div>
                            <label class="block text-lg font-bold text-gray-800 mb-6 text-center">
                                <i class="fas fa-users mr-2 text-indigo-600"></i>
                                Select Your Account Type
                            </label>
                            <div class="grid md:grid-cols-3 gap-6">
                                <div class="user-type-card border-2 rounded-2xl p-6 text-center cursor-pointer"
                                     :class="selectedUserType === 'public_user' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200 hover:border-blue-300 bg-white'"
                                     @click="selectedUserType = 'public_user'">
                                    <div class="text-4xl mb-4" 
                                         :class="selectedUserType === 'public_user' ? 'text-blue-600' : 'text-gray-400'">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <h3 class="font-bold text-lg text-gray-800 mb-2">Public User</h3>
                                    <p class="text-sm text-gray-600">For general public access</p>
                                </div>
                                
                                <div class="user-type-card border-2 rounded-2xl p-6 text-center cursor-pointer"
                                     :class="selectedUserType === 'agency' ? 'border-green-500 bg-green-50 shadow-lg' : 'border-gray-200 hover:border-green-300 bg-white'"
                                     @click="selectedUserType = 'agency'">
                                    <div class="text-4xl mb-4"
                                         :class="selectedUserType === 'agency' ? 'text-green-600' : 'text-gray-400'">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h3 class="font-bold text-lg text-gray-800 mb-2">Agency</h3>
                                    <p class="text-sm text-gray-600">Government agencies</p>
                                </div>
                                
                                <div class="user-type-card border-2 rounded-2xl p-6 text-center cursor-pointer"
                                     :class="selectedUserType === 'mcmc' ? 'border-red-500 bg-red-50 shadow-lg' : 'border-gray-200 hover:border-red-300 bg-white'"
                                     @click="selectedUserType = 'mcmc'">
                                    <div class="text-4xl mb-4"
                                         :class="selectedUserType === 'mcmc' ? 'text-red-600' : 'text-gray-400'">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h3 class="font-bold text-lg text-gray-800 mb-2">MCMC</h3>
                                    <p class="text-sm text-gray-600">MCMC staff only</p>
                                </div>
                            </div>
                            <input type="hidden" name="user_type" :value="selectedUserType">
                        </div>

                        <!-- Login Fields -->
                        <div class="space-y-6">
                            <!-- Email Field (Public Users) -->
                            <div x-show="selectedUserType === 'public_user'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="your.email@example.com"
                                       x-bind:required="selectedUserType === 'public_user'">
                            </div>
                            
                            <!-- Username Field (Agency & MCMC) -->
                            <div x-show="selectedUserType === 'agency' || selectedUserType === 'mcmc'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                <label for="username" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-user mr-2" :class="selectedUserType === 'agency' ? 'text-green-500' : 'text-red-500'"></i>Username
                                </label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       placeholder="Enter your username"
                                       x-bind:required="selectedUserType === 'agency' || selectedUserType === 'mcmc'">
                            </div>

                            <!-- Password Field -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg pr-12" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <div>
                            <button type="submit" class="login-button w-full bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-lg">
                                <i class="fas fa-sign-in-alt mr-3"></i>
                                Sign In to Your Account
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center pt-6 border-t border-gray-200">
                            <p class="text-gray-600 mb-3">Don't have an account yet?</p>
                            <a href="{{ route('register') }}" class="inline-flex items-center text-blue-600 hover:text-purple-600 font-semibold transition-colors duration-200 text-lg">
                                <i class="fas fa-user-plus mr-2"></i>
                                Create New Account
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center mt-8">
                <a href="{{ route('welcome') }}" class="inline-flex items-center text-white hover:text-blue-200 transition-colors duration-200 text-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Enhanced fallback JavaScript for browsers without Alpine.js
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Alpine.js is loaded
            if (typeof Alpine === 'undefined') {
                const userTypeInput = document.querySelector('input[name="user_type"]');
                const emailField = document.querySelector('#email');
                const usernameField = document.querySelector('#username');
                const userTypeCards = document.querySelectorAll('.user-type-card');
                
                let selectedUserType = userTypeInput.value || 'public_user';
                
                function updateFields(userType) {
                    userTypeInput.value = userType;
                    
                    // Show/hide fields
                    const emailContainer = emailField.closest('div');
                    const usernameContainer = usernameField.closest('div');
                    
                    if (userType === 'public_user') {
                        emailContainer.style.display = 'block';
                        usernameContainer.style.display = 'none';
                        emailField.required = true;
                        usernameField.required = false;
                    } else {
                        emailContainer.style.display = 'none';
                        usernameContainer.style.display = 'block';
                        emailField.required = false;
                        usernameField.required = true;
                    }
                    
                    // Update card styling
                    userTypeCards.forEach((card, index) => {
                        const cardTypes = ['public_user', 'agency', 'mcmc'];
                        const colors = ['blue', 'green', 'red'];
                        
                        card.classList.remove(border-${colors[0]}-500, bg-${colors[0]}-50, 
                                            border-${colors[1]}-500, bg-${colors[1]}-50,
                                            border-${colors[2]}-500, bg-${colors[2]}-50);
                        
                        if (cardTypes[index] === userType) {
                            card.classList.add(border-${colors[index]}-500, bg-${colors[index]}-50, 'shadow-lg');
                        } else {
                            card.classList.add('border-gray-200', 'bg-white');
                        }
                    });
                }
                
                // Initialize
                updateFields(selectedUserType);
                
                // Add click handlers
                userTypeCards.forEach((card, index) => {
                    const cardTypes = ['public_user', 'agency', 'mcmc'];
                    card.addEventListener('click', function() {
                        selectedUserType = cardTypes[index];
                        updateFields(selectedUserType);
                    });
                });
            }
        });

        // Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Signing In...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>