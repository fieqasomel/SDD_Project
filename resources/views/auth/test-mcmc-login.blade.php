<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test MCMC Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 12px 24px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Test MCMC Login</h1>
    
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
        
        <input type="hidden" name="user_type" value="mcmc">
        
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit">Login as MCMC</button>
    </form>
    
    <hr style="margin: 40px 0;">
    
    <h2>Test Credentials:</h2>
    <p><strong>Username:</strong> MCMC</p>
    <p><strong>Password:</strong> password123</p>
    
    <h2>Available MCMC Users:</h2>
    @php
        $mcmcUsers = \App\Models\MCMC::all();
    @endphp
    
    @foreach($mcmcUsers as $user)
        <p>ID: {{ $user->M_ID }}, Username: {{ $user->M_userName }}, Name: {{ $user->M_Name }}</p>
    @endforeach
</body>
</html>