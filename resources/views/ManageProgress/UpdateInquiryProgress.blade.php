<!DOCTYPE html>
<html>
<head>
    <title>Update Inquiry Progress</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2 { color: #2c3e50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], textarea, select {
            width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;
        }
        button { margin-top: 20px; padding: 10px 15px; background-color: #3498db; color: white; border: none; }
        .alert { padding: 10px; margin-top: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <h2>Update Progress for Inquiry ID: {{ $inquiry->id }}</h2>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('inquiry.progress.update', $inquiry->id) }}" method="POST">
        @csrf

        <label for="status">Status:</label>
        <select name="status" required>
            <option value="">-- Select Status --</option>
            <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Resolved" {{ old('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" rows="4" required>{{ old('description') }}</textarea>

        <label for="updated_date">Update Date:</label>
        <input type="date" name="updated_date" value="{{ old('updated_date') }}" required>

        <button type="submit">Update Progress</button>
    </form>
</body>
</html>
