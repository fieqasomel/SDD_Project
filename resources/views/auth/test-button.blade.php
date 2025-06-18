<!DOCTYPE html>
<html>
<head>
    <title>Button Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .container { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
        button { padding: 10px 20px; margin: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        input { padding: 10px; margin: 10px; width: 200px; }
        .result { margin: 20px 0; padding: 10px; background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Button Test</h2>
        
        <div class="result" id="result">Click buttons to test...</div>
        
        <!-- Test 1: Simple button -->
        <button onclick="testClick(1)">Test Button 1</button>
        
        <!-- Test 2: Form with button -->
        <form onsubmit="testSubmit(event, 2)">
            @csrf
            <input type="text" placeholder="Test input" required>
            <button type="submit">Test Form Submit</button>
        </form>
        
        <!-- Test 3: MCMC Login Form -->
        <form method="POST" action="{{ route('login') }}" onsubmit="testSubmit(event, 3)">
            @csrf
            <input type="hidden" name="user_type" value="mcmc">
            <input type="text" name="username" value="MCMC" placeholder="Username" required>
            <input type="password" name="password" value="password123" placeholder="Password" required>
            <button type="submit">MCMC Login Test</button>
        </form>
        
        <!-- Test 4: Test POST route -->
        <form method="POST" action="/test-login" onsubmit="testSubmit(event, 4)">
            @csrf
            <input type="text" name="test" value="hello" required>
            <button type="submit">Test POST Route</button>
        </form>
    </div>

    <script>
        function testClick(testNum) {
            document.getElementById('result').innerHTML = 'Test ' + testNum + ' clicked at ' + new Date().toLocaleTimeString();
            console.log('Test ' + testNum + ' clicked');
        }
        
        function testSubmit(event, testNum) {
            document.getElementById('result').innerHTML = 'Test ' + testNum + ' form submitted at ' + new Date().toLocaleTimeString();
            console.log('Test ' + testNum + ' form submitted');
            
            if (testNum === 2) {
                event.preventDefault(); // Prevent actual submission for test 2
                document.getElementById('result').innerHTML += ' (prevented)';
            }
            // For tests 3 and 4, let the form submit normally
        }
        
        console.log('Page loaded successfully');
    </script>
</body>
</html>