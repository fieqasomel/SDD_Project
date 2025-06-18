<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Simple Login - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Simple Login Test</h2>
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- User Type Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">User Type</label>
                    <select name="user_type" id="userType" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <option value="public_user">Public User</option>
                        <option value="agency">Agency</option>
                        <option value="mcmc">MCMC</option>
                    </select>
                </div>

                <!-- Email field (for public user) -->
                <div class="mb-4" id="emailField">
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}">
                </div>
                
                <!-- Username field (for agency and mcmc) -->
                <div class="mb-4" id="usernameField" style="display: none;">
                    <label for="username" class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <input type="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" 
                           id="password" 
                           name="password" 
                           required>
                </div>

                <!-- Login Button -->
                <div class="mb-4">
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            id="loginButton">
                        Login
                    </button>
                </div>
            </form>

            <!-- Debug Info -->
            <div class="mt-6 p-4 bg-gray-100 rounded text-sm">
                <h4 class="font-bold mb-2">Test Credentials:</h4>
                <p><strong>MCMC:</strong> Username: MCMC, Password: password123</p>
                <p><strong>Agency:</strong> Username: testuser, Password: password123</p>
                <p><strong>Public:</strong> Email: test@example.com, Password: password123</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('userType');
            const emailField = document.getElementById('emailField');
            const usernameField = document.getElementById('usernameField');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            function updateFields() {
                const userType = userTypeSelect.value;
                console.log('User type changed to:', userType);
                
                if (userType === 'public_user') {
                    emailField.style.display = 'block';
                    usernameField.style.display = 'none';
                    emailInput.required = true;
                    usernameInput.required = false;
                } else {
                    emailField.style.display = 'none';
                    usernameField.style.display = 'block';
                    emailInput.required = false;
                    usernameInput.required = true;
                }
            }

            // Initialize
            updateFields();

            // Listen for user type changes
            userTypeSelect.addEventListener('change', updateFields);

            // Add form submit handler for debugging
            loginForm.addEventListener('submit', function(e) {
                console.log('Form submitted');
                console.log('User type:', userTypeSelect.value);
                console.log('Email:', emailInput.value);
                console.log('Username:', usernameInput.value);
                console.log('Password length:', document.getElementById('password').value.length);
                
                // Don't prevent default - let form submit normally
                // e.preventDefault(); // Remove this line to allow normal submission
            });

            // Add button click handler for debugging
            loginButton.addEventListener('click', function(e) {
                console.log('Login button clicked');
            });
        });
=======
    <title>Login - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .user-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .user-type-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .user-type-card.active {
            border-color: #3b82f6 !important;
            background-color: #eff6ff !important;
        }
        .user-type-card.active.agency {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
        .user-type-card.active.mcmc {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        .hidden { display: none !important; }
        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border: none;
            padding: 16px 24px;
            border-radius: 9999px;
            color: white;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .btn-login:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl mx-auto">
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
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- User Type Selection -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Select User Type</label>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="user-type-card public_user border-2 border-gray-200 rounded-xl p-4 text-center active"
                                 onclick="selectUserType('public_user')">
                                <div class="text-3xl text-blue-600 mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">Public User</h6>
                                <small class="text-gray-600">General Users</small>
                            </div>
                            <div class="user-type-card agency border-2 border-gray-200 rounded-xl p-4 text-center"
                                 onclick="selectUserType('agency')">
                                <div class="text-3xl text-green-600 mb-3">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">Agency</h6>
                                <small class="text-gray-600">Government Agencies</small>
                            </div>
                            <div class="user-type-card mcmc border-2 border-gray-200 rounded-xl p-4 text-center"
                                 onclick="selectUserType('mcmc')">
                                <div class="text-3xl text-red-600 mb-3">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h6 class="font-semibold text-gray-800 mb-1">MCMC</h6>
                                <small class="text-gray-600">MCMC Staff</small>
                            </div>
                        </div>
                        <input type="hidden" name="user_type" id="user_type" value="public_user">
                    </div>

                    <!-- Email field for Public User -->
                    <div class="mb-6" id="email-field">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email address">
                    </div>
                    
                    <!-- Username field for Agency and MCMC -->
                    <div class="mb-6 hidden" id="username-field">
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-500"></i>Username
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}" 
                               placeholder="Enter your username">
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
                        <button type="submit" class="btn-login" id="loginButton">
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

    <!-- Simple JavaScript -->
    <script>
        let selectedUserType = 'public_user';
        
        function selectUserType(userType) {
            console.log('Selecting user type:', userType);
            
            selectedUserType = userType;
            document.getElementById('user_type').value = userType;
            
            // Update card styling
            const cards = document.querySelectorAll('.user-type-card');
            cards.forEach(card => {
                card.classList.remove('active');
            });
            
            const selectedCard = document.querySelector('.user-type-card.' + userType);
            if (selectedCard) {
                selectedCard.classList.add('active');
            }
            
            // Show/hide appropriate fields
            const emailField = document.getElementById('email-field');
            const usernameField = document.getElementById('username-field');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            
            if (userType === 'public_user') {
                emailField.classList.remove('hidden');
                usernameField.classList.add('hidden');
                emailInput.required = true;
                usernameInput.required = false;
            } else {
                emailField.classList.add('hidden');
                usernameField.classList.remove('hidden');
                emailInput.required = false;
                usernameInput.required = true;
            }
            
            console.log('User type selected:', userType);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded');
            selectUserType('public_user');
            
            // Test button click
            document.getElementById('loginButton').addEventListener('click', function(e) {
                console.log('Login button clicked!');
                console.log('Selected user type:', selectedUserType);
                console.log('Form data:', new FormData(document.getElementById('loginForm')));
            });
        });
        
        // Debug function - you can call this in browser console
        window.debugLogin = function() {
            console.log('Current user type:', selectedUserType);
            console.log('Form element:', document.getElementById('loginForm'));
            console.log('Button element:', document.getElementById('loginButton'));
            console.log('All form data:', new FormData(document.getElementById('loginForm')));
        };
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
    </script>
</body>
</html>