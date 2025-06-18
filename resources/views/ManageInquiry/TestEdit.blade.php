<!DOCTYPE html>
<html>
<head>
    <title>Edit Test</title>
</head>
<body>
    <h1>Edit Test Page</h1>
    <p>Test edit function</p>
    
    @if(isset($inquiry))
        <p>Inquiry ID: {{ $inquiry->I_ID }}</p>
        <p>Title: {{ $inquiry->I_Title }}</p>
        <p>Status: {{ $inquiry->I_Status }}</p>
    @else
        <p>No inquiry data found</p>
    @endif
    
    @if(isset($categories))
        <p>Categories available: {{ count($categories) }}</p>
    @else
        <p>No categories found</p>
    @endif
</body>
</html>