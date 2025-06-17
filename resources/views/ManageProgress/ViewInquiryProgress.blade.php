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

<h2>Inquiry Progress Records</h2>

<table>
    <thead>
        <tr>
            <th>Inquiry ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Last Updated</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($progressRecords as $progress)
            <tr>
                <td>{{ $progress->I_ID }}</td>
                <td>{{ $progress->P_Title }}</td>
                <td>{{ $progress->P_Status }}</td>
                <td>{{ $progress->P_Date->format('Y-m-d') }}</td>
                <td>{{ $progress->P_Description }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No progress updates found for this inquiry.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
