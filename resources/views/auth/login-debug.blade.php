<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Login - MySebenarnya System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select { padding: 12px; width: 100%; border: 2px solid #ddd; border-radius: 6px; font-size: 16px; }
        input:focus, select:focus { border-color: #007bff; outline: none; }
        button { padding: 15px 30px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; }
        button:hover { background: #0056b3; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        .error { color: red; margin-top: 10px; padding: 10px; background: #ffe6e6; border-radius: 4px; }
        .success { color: green; margin-top: 10px; padding: 10px; background: #e6ffe6; border-radius: 4px; }
        .user-type-selector { display: flex; gap: 10px; margin-bottom: 20px; }
        .user-type-btn { flex: 1; padding: 10px; border: 2px solid #ddd; background: white; cursor: pointer; border-radius: 6px; text-align: center; }
        .user-type-btn.active { border-color: #007bff; background: #e7f3ff; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">
            <i class="fas fa-shield-halved"></i> Login Debug
        </h1>
        
        @if ($errors->any())
            <div class="error">
                <strong>Errors:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <!-- User Type Selection -->
            <div class="form-group">
                <label>Select Account Type:</label>
                <div class="user-type-selector">
                    <div class="user-type-btn active" onclick="selectUserType('public_user')">
                        <i class="fas fa-user-circle"></i><br>Public User
                    </div>
                    <div class="user-type-btn" onclick="selectUserType('agency')">
                        <i class="fas fa-building"></i><br>Agency
                    </div>
                    <div class="user-type-btn" onclick="selectUserType('mcmc')">
                        <i class="fas fa-shield-alt"></i><br>MCMC
                    </div>
                </div>
                <input type="hidden" name="user_type" id="userType" value="public_user">
            </div>
            
            <!-- Email Field (Public Users) -->
            <div class="form-group" id="emailField">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com">
            </div>
            
            <!-- Username Field (Agency & MCMC) -->
            <div class="form-group hidden" id="usernameField">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter your username">
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #666; width: auto; padding: 5px;">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" id="submitBtn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 6px;">
            <h3>Test Credentials:</h3>
            <p><strong>MCMC:</strong> Username: MCMC, Password: password123</p>
            <p><strong>Agency:</strong> Username: BOMBA, Password: password123</p>
            <p><strong>Public:</strong> Email: AFIQAH@gmail.com, Password: password123</p>
        </div>
    </div>

    <script>
        function selectUserType(type) {
            // Update hidden input
            document.getElementById('userType').value = type;
            
            // Update button styles
            document.querySelectorAll('.user-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.user-type-btn').classList.add('active');
            
            // Show/hide fields
            const emailField = document.getElementById('emailField');
            const usernameField = document.getElementById('usernameField');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            
            if (type === 'public_user') {
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
        }
        
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
        
        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            submitBtn.disabled = true;
            
            // Re-enable button after 5 seconds in case of error
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                submitBtn.disabled = false;
            }, 5000);
        });
        
        // Initialize form
        selectUserType('public_user');
    </script>
</body>
</html>