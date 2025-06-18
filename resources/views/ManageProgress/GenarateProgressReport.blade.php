<!DOCTYPE html>
<html>
<head>
    <title>Inquiry Progress Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2, h3 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cccccc; padding: 10px; text-align: left; }
        th { background-color: #f0f0f0; }
        form label, form input, form select { margin-right: 10px; }
    </style>
</head>
<body>

<h2>Inquiry Progress Report</h2>

<form method="GET" action="{{ route('report.progress') }}">
    <label for="start_date">From:</label>
    <input type="date" name="start_date" value="{{ request('start_date') }}">

    <label for="end_date">To:</label>
    <input type="date" name="end_date" value="{{ request('end_date') }}">

    <label for="status">Status:</label>
    <select name="status">
        <option value="All">All</option>
        @foreach(['Pending', 'In Progress', 'Resolved', 'Rejected'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    <button type="submit">Generate</button>
</form>

<h3>Summary by Agency</h3>
<table>
    <thead>
        <tr>
            <th>Agency</th>
            <th>Total Inquiries</th>
            <th>Resolved</th>
            <th>Pending</th>
            <th>Avg. Resolution Time (Days)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($summary as $agency => $data)
        <tr>
            <td>{{ $agency }}</td>
            <td>{{ $data['total'] }}</td>
            <td>{{ $data['resolved'] }}</td>
            <td>{{ $data['pending'] }}</td>
            <td>{{ $data['avg_days'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Detailed Inquiry Records</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Submitted By</th>
            <th>Agency</th>
            <th>Submission Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiries as $inq)
        <tr>
            <td>{{ $inq->I_ID }}</td>
            <td>{{ $inq->I_Title }}</td>
            <td>{{ $inq->I_Status }}</td>
            <td>{{ $inq->publicUser->PU_Name ?? '-' }}</td>
            <td>{{ optional($inq->complaint->agency)->A_Name ?? 'Unassigned' }}</td>
            <td>{{ $inq->I_Date->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
