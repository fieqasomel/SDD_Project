<!-- ViewInquiryProgress.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>View Inquiry Progress</title>
</head>
<body>
    <h2>Progress History</h2>

    <table border="1">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        @foreach ($progressRecords as $progress)
            <tr>
                <td>{{ $progress->P_Title }}</td>
                <td>{{ $progress->P_Description }}</td>
                <td>{{ $progress->P_Date }}</td>
                <td>{{ $progress->P_Status }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
