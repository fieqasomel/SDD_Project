<!DOCTYPE html>
<html>
<head>
    <title>View Inquiry Progress</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Progress History for Inquiry ID: {{ $inquiry->id }}</h2>

    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Description</th>
                <th>Updated Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($progress as $record)
                <tr>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->updated_date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
