<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test - MySebenarnya System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .debug {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login Form Test</h1>
        <p>This is a simplified login form to test form submission.</p>

        @if ($errors->any())
            <div class="error">
                <strong>Errors:</strong>
                <ul>
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
            
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <select name="user_type" id="user_type" required>
                    <option value="public_user" {{ old('user_type') == 'public_user' ? 'selected' : '' }}>Public User</option>
                    <option value="agency" {{ old('user_type') == 'agency' ? 'selected' : '' }}>Agency</option>
                    <option value="mcmc" {{ old('user_type') == 'mcmc' ? 'selected' : '' }}>MCMC</option>
                </select>
            </div>

            <div class="form-group" id="email-group">
                <label for="email">Email (for Public User):</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="test@example.com">
            </div>

            <div class="form-group" id="username-group" style="display: none;">
                <label for="username">Username (for Agency/MCMC):</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="testagency or testmcmc">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required placeholder="password123">
            </div>

            <button type="submit" id="submitBtn">Login</button>
        </form>

        <div class="debug">
            <strong>Debug Info:</strong><br>
            CSRF Token: {{ csrf_token() }}<br>
            Route: {{ route('login') }}<br>
            Old Input: {{ json_encode(old()) }}
        </div>

        <div class="debug">
            <strong>Test Credentials:</strong><br>
            Public User: test@example.com / password123<br>
            Agency: testagency / password123<br>
            MCMC: testmcmc / password123
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('user_type');
            const emailGroup = document.getElementById('email-group');
            const usernameGroup = document.getElementById('username-group');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            function toggleFields() {
                const userType = userTypeSelect.value;
                
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

            userTypeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initialize

            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted...');
                submitBtn.innerHTML = 'Logging in...';
                submitBtn.disabled = true;
                
                // Log form data for debugging
                const formData = new FormData(form);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
            });
        });
    </script>
</body>
</html>