<!DOCTYPE html>
<html>
<head>
    <title>Inquiry Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            color: #2c3e50;
        }

        .notification-container {
            margin-top: 20px;
        }

        .notification {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .notification-time {
            font-size: 0.9em;
            color: #999;
        }

        .notification-message {
            margin-top: 5px;
        }
    </style>
</head>
<body>

<h2>Inquiry Notifications</h2>

<div class="notification-container">
    @forelse ($notifications as $note)
        <div class="notification">
            <div class="notification-time">{{ \Carbon\Carbon::parse($note->P_Date)->format('Y-m-d h:i A') }}</div>
            <div class="notification-message">
                Inquiry <strong>#{{ $note->I_ID }}</strong> ({{ $note->I_Title }}) has been updated to 
                <strong>"{{ $note->P_Status }}"</strong><br>
                <em>{{ $note->P_Title }}: {{ $note->P_Description }}</em>
            </div>
        </div>
    @empty
        <p>No notifications available.</p>
    @endforelse
</div>

</body>
</html>
