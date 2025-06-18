<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </script>
</body>
</html>