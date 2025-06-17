<!DOCTYPE html>
<html>
<head>
    <title>View Inquiry Progress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .search-bar {
            margin-bottom: 15px;
        }

        input[type="text"] {
            padding: 6px;
            width: 300px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 6px 12px;
            background-color: #007BFF;
            border: none;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Inquiry Progress</h2>

<div class="search-bar">
    <form method="GET" action="{{ route('progress.view', ['inquiry_id' => request('inquiry_id')]) }}">
        <input type="text" name="inquiry_id" placeholder="Enter Inquiry ID..." value="{{ request('inquiry_id') }}">
        <input type="submit" value="Search">
    </form>
</div>

@if(isset($progressRecords) && count($progressRecords))
    <table>
        <thead>
            <tr>
                <th>Inquiry ID</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Updated By</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progressRecords as $progress)
                <tr>
                    <td>{{ $progress->inquiry_id }}</td>
                    <td>{{ $progress->status }}</td>
                    <td>{{ $progress->comment }}</td>
                    <td>{{ $progress->updated_by }}</td>
                    <td>{{ $progress->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif(request()->has('inquiry_id'))
    <p>No progress found for Inquiry ID: <strong>{{ request('inquiry_id') }}</strong></p>
@endif

</body>
</html>
