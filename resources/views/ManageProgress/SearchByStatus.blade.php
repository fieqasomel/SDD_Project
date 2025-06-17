<!DOCTYPE html>
<html>
<head>
    <title>Search Inquiries by Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        select, button {
            padding: 8px;
            font-size: 1em;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .details-box {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #aaa;
            background-color: #f9f9f9;
        }

        .view-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .view-button:hover {
            background-color: #2c80b4;
        }

        .file-link {
            display: block;
            margin-top: 5px;
            color: #2980b9;
            text-decoration: none;
        }
    </style>
    <script>
        function toggleDetails(id) {
            const box = document.getElementById('details-' + id);
            box.style.display = (box.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<h2>Search Inquiries by Status</h2>

<form method="GET" action="{{ route('inquiries.search.status') }}">
    <label for="status">Select Inquiry Status:</label>
    <select id="status" name="status">
        <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
        <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="In Review" {{ $status == 'In Review' ? 'selected' : '' }}>In Review</option>
        <option value="Resolved" {{ $status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
        <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <button type="submit">Search</button>
</form>

@if($inquiries->isEmpty())
    <p>No inquiries found.</p>
@else
<table>
    <thead>
        <tr>
            <th>Inquiry ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Submitted Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiries as $inquiry)
        <tr>
            <td>{{ $inquiry->id }}</td>
            <td>{{ $inquiry->title }}</td>
            <td>{{ $inquiry->status }}</td>
            <td>{{ $inquiry->created_at->format('Y-m-d') }}</td>
            <td>
                <button class="view-button" onclick="toggleDetails('{{ $inquiry->id }}')">View Details</button>
            </td>
        </tr>
        <tr id="details-{{ $inquiry->id }}" class="details-box">
            <td colspan="5">
                <strong>Description:</strong> {{ $inquiry->description }}<br>
                @if($inquiry->attachment)
                    <strong>Attached File:</strong>
                    <a class="file-link" href="{{ asset('storage/' . $inquiry->attachment) }}" target="_blank">Download</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>
