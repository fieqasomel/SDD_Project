<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Login - SDD System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 15px; background: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .debug { background: #e2e3e5; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Debug Login Form</h2>
        
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="debugForm">
            @csrf
            
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <select name="user_type" id="user_type" required onchange="toggleFields()">
                    <option value="public_user" {{ old('user_type') == 'public_user' ? 'selected' : '' }}>Public User</option>
                    <option value="agency" {{ old('user_type') == 'agency' ? 'selected' : '' }}>Agency</option>
                    <option value="mcmc" {{ old('user_type') == 'mcmc' ? 'selected' : '' }}>MCMC</option>
                </select>
            </div>

            <div class="form-group" id="email_group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email">
            </div>
            
            <div class="form-group" id="username_group" style="display: none;">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Enter username">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required placeholder="Enter password">
            </div>

            <button type="submit" onclick="console.log('Button clicked!'); return true;">
                LOGIN
            </button>
        </form>

        <div class="debug">
            <h4>Test Credentials:</h4>
            <p><strong>MCMC:</strong> Username: MCMC, Password: password123</p>
            <p><strong>Agency:</strong> Username: testuser, Password: password123</p>
            <p><strong>Public:</strong> Email: test@example.com, Password: password123</p>
            
            <h4>Debug Info:</h4>
            <p>CSRF Token: {{ csrf_token() }}</p>
            <p>Form Action: {{ route('login') }}</p>
            <p>Old Input: {{ json_encode(old()) }}</p>
        </div>
    </div>

    <script>
        function toggleFields() {
            const userType = document.getElementById('user_type').value;
            const emailGroup = document.getElementById('email_group');
            const usernameGroup = document.getElementById('username_group');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            
            console.log('User type changed to:', userType);
            
            if (userType === 'public_user') {
                emailGroup.style.display = 'block';
                usernameGroup.style.display = 'none';
                emailInput.required = true;
                usernameInput.required = false;
            } else {
                emailGroup.style.display = 'none';
                usernameGroup.style.display = 'block';
                emailInput.required = false;
                usernameInput.required = true;
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded');
            toggleFields();
            
            // Add form submit listener
            document.getElementById('debugForm').addEventListener('submit', function(e) {
                console.log('Form submitted!');
                console.log('User type:', document.getElementById('user_type').value);
                console.log('Email:', document.getElementById('email').value);
                console.log('Username:', document.getElementById('username').value);
                console.log('Password length:', document.getElementById('password').value.length);
                // Don't prevent default - let form submit
            });
        });
    </script>
</body>
</html>