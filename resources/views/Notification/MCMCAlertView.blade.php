<!DOCTYPE html>
<html>
<head>
    <title>MCMC Alert View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f2f2f2;
        }

        h2 {
            color: #c0392b;
        }

        .alert-container {
            margin-top: 20px;
        }

        .alert {
            border-left: 5px solid #e74c3c;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .alert-time {
            font-size: 0.9em;
            color: #7f8c8d;
        }

        .alert-message {
            font-weight: bold;
            color: #2c3e50;
        }

        .alert-type {
            font-size: 0.85em;
            color: #e74c3c;
            font-style: italic;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<h2>MCMC Alerts</h2>

<div class="alert-container">
    @forelse ($alerts as $alert)
        <div class="alert">
            <div class="alert-time">{{ \Carbon\Carbon::parse($alert['time'])->format('Y-m-d h:i A') }}</div>
            <div class="alert-message">{{ $alert['message'] }}</div>
            <div class="alert-type">[{{ $alert['type'] }}]</div>
        </div>
    @empty
        <p>No alerts available.</p>
    @endforelse
</div>

</body>
</html>
