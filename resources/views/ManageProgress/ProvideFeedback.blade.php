<!DOCTYPE html>
<html>
<head>
    <title>Provide Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #2c3e50;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        textarea {
            resize: vertical;
            height: 120px;
        }

        button[type="submit"] {
            margin-top: 20px;
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #1c5980;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Provide Inquiry Feedback</h2>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('submit.feedback') }}">
        @csrf

        <label>Inquiry</label>
        <select name="inquiry_id" required>
            <option value="">-- Select Inquiry --</option>
            @foreach ($inquiries as $inquiry)
                <option value="{{ $inquiry->I_ID }}">{{ $inquiry->I_ID }} - {{ $inquiry->I_Title }}</option>
            @endforeach
        </select>

        <label>Investigation Notes</label>
        <input type="text" name="p_title" required>

        <label>Explanation</label>
        <textarea name="p_description" required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>

</body>
</html>
