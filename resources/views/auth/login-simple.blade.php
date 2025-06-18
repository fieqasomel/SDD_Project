<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; }
        .user-type { margin: 10px 0; }
        .credentials { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Simple Login Test</h1>
    
    <div class="credentials">
        <h3>Test Credentials:</h3>
        <p><strong>PublicUser:</strong> test@example.com / password123</p>
        <p><strong>Agency:</strong> testagency / password123</p>
        <p><strong>MCMC:</strong> testmcmc / password123</p>
    </div>

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

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label>User Type:</label>
            <select name="user_type" id="user_type" onchange="toggleFields()">
                <option value="public_user" {{ old('user_type') == 'public_user' ? 'selected' : '' }}>Public User</option>
                <option value="agency" {{ old('user_type') == 'agency' ? 'selected' : '' }}>Agency</option>
                <option value="mcmc" {{ old('user_type') == 'mcmc' ? 'selected' : '' }}>MCMC</option>
            </select>
        </div>

        <div class="form-group" id="email-field">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="test@example.com">
        </div>

        <div class="form-group" id="username-field" style="display: none;">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="testagency or testmcmc">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="password123" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <script>
        function toggleFields() {
            const userType = document.getElementById('user_type').value;
            const emailField = document.getElementById('email-field');
            const usernameField = document.getElementById('username-field');
            
            if (userType === 'public_user') {
                emailField.style.display = 'block';
                usernameField.style.display = 'none';
                document.getElementById('email').required = true;
                document.getElementById('username').required = false;
            } else {
                emailField.style.display = 'none';
                usernameField.style.display = 'block';
                document.getElementById('email').required = false;
                document.getElementById('username').required = true;
            }
        }
        
        // Initialize on page load
        toggleFields();
    </script>
</body>
</html>