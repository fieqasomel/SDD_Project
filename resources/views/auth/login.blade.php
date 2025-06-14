<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback script in case Alpine.js doesn't load properly -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Alpine is loaded after a short delay
            setTimeout(function() {
                if (typeof window.Alpine === 'undefined') {
                    console.error('Alpine.js not loaded, using fallback');
                    
                    // Fallback for user type selection
                    const userTypeCards = document.querySelectorAll('.user-type-card');
                    const userTypeInput = document.querySelector('input[name="user_type"]');
                    const emailField = document.querySelector('#email').parentNode;
                    const usernameField = document.querySelector('#username').parentNode;
                    
                    // Set initial state
                    const initialType = userTypeInput.value || 'public_user';
                    updateFields(initialType);
                    
                    userTypeCards.forEach(card => {
                        card.addEventListener('click', function() {
                            const userType = this.querySelector('h6').textContent.toLowerCase().replace(' ', '_');
                            userTypeInput.value = userType;
                            updateFields(userType);
                            
                            // Update active state
                            userTypeCards.forEach(c => c.classList.remove('border-blue-500', 'border-green-500', 'border-red-500', 'bg-blue-50', 'bg-green-50', 'bg-red-50'));
                            if (userType === 'public_user') {
                                this.classList.add('border-blue-500', 'bg-blue-50');
                            } else if (userType === 'agency') {
                                this.classList.add('border-green-500', 'bg-green-50');
                            } else if (userType === 'mcmc') {
                                this.classList.add('border-red-500', 'bg-red-50');
                            }
                        });
                    });
                    
                    function updateFields(userType) {
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
                    }
                }
            }, 500);
        });
    </script>
    
    <!-- Fallback script in case Alpine.js doesn't load properly -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Alpine is loaded after a short delay
            setTimeout(function()  x-init="console.log('Alpine initialized with user type:', selectedUserType)"{
                if (typeof window.Alpine === 'undefined') {
                    console.error('Alpine.js not loaded, using fallback');
                    
                    // Fallback for user type selection
                    const userTypeCards = document.querySelectorAll('.user-type-card');
                    const userTypeInput = document.querySelector('input[name="user_type"]');
                    const emailField = document.querySelector('#email').parentNode;
                    const usernameField = document.querySelector('#username').parentNode;
                    
                    // Set initial state
                    const initialType = userTypeInput.value || 'public_user';
                    updateFields(initialType);
                    
                    userTypeCards.forEach(card => {
                        card.addEventListener('click', function() {
                            const userType = this.querySelector('h6').textContent.toLowerCase().replace(' ', '_');
                            userTypeInput.value = userType;
                            updateFields(userType);
                            
                            // Update active state
                            userTypeCards.forEach(c => c.classList.remove('border-blue-500', 'border-green-500', 'border-red-500', 'bg-blue-50', 'bg-green-50', 'bg-red-50'));
                            if (userType === 'public_user') {
                                this.classList.add('border-blue-500', 'bg-blue-50');
                            } else if (userType === 'agency') {
                                this.classList.add('border-green-500', 'bg-green-50');
                            } else if (userType === 'mcmc') {
                                this.classList.add('border-red-500', 'bg-red-50');
                            }
                        });
                    });
                    
                    function updateFields(userType) {
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
                    }
                }
            }, 500);
        });
    </script>
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
                    <h2 class="text-3xl font-bold mb-2">Login to MySebenarnya</h2>
                    <p class="text-lg opacity-90">Select your user type and enter your credentials</p>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="p-8">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                            <span class="font-semibold">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside ml-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li style="display: none;" x-transition>
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
                                <div class="text-3xl text-blue-600 mb-3" style="display: none;" x-transition>
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
                    <div class="mb-6" x-show="selectedUserType === 'public_user'" style="display: none;" x-transition>
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
                    <div class="mb-6" x-show="selectedUserType === 'agency' || selectedUserType === 'mcmc'" style="display: none;" x-transition>
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

    <!-- Ensure fields are properly shown/hidden on page load -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the current user type
            const userType = document.querySelector('input[name="user_type"]').value;
            
            // Get the email and username fields
            const emailField = document.querySelector('#email').closest('div');
            const usernameField = document.querySelector('#username').closest('div');
            
            // Show/hide fields based on user type
            if (userType === 'public_user') {
                emailField.style.display = 'block';
                usernameField.style.display = 'none';
            } else {
                emailField.style.display = 'none';
                usernameField.style.display = 'block';
            }
            
            // Add click handlers to user type cards
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Get the user type from the card
                    const cardType = this.querySelector('h6').textContent.toLowerCase().replace(' ', '_');
                    
                    // Show/hide fields based on selected user type
                    if (cardType === 'public_user') {
                        emailField.style.display = 'block';
                        usernameField.style.display = 'none';
                    } else {
                        emailField.style.display = 'none';
                        usernameField.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>