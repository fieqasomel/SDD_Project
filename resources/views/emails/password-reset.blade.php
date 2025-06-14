<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #f59e0b;
            color: white;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 30px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .warning-icon {
            color: #f59e0b;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .link-text {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Password Reset Request</h1>
            <p>MCMC System</p>
        </div>

        <h2>Hello,</h2>
        
        <p>We received a request to reset the password for your {{ ucfirst(str_replace('_', ' ', $userType)) }} account in the MCMC Inquiry Management System.</p>

        <p>If you made this request, click the button below to reset your password:</p>

        <div class="button-container">
            <a href="{{ $resetUrl }}" class="button">🔄 Reset My Password</a>
        </div>

        <p>If the button doesn't work, you can copy and paste the following link into your browser:</p>
        <div class="link-text">{{ $resetUrl }}</div>

        <div class="warning">
            <span class="warning-icon">⚠️</span>
            <strong>Security Notice:</strong>
            <ul style="margin: 10px 0;">
                <li>This password reset link will expire in 1 hour</li>
                <li>If you didn't request this password reset, you can safely ignore this email</li>
                <li>Your password will remain unchanged until you create a new one</li>
                <li>For security reasons, never share this link with anyone</li>
            </ul>
        </div>

        <h3>Account Information:</h3>
        <ul>
            @if($userType === 'public_user')
                <li><strong>Account Type:</strong> Public User</li>
                <li><strong>Name:</strong> {{ $user->PU_Name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $user->PU_Email ?? 'N/A' }}</li>
            @elseif($userType === 'agency')
                <li><strong>Account Type:</strong> Agency</li>
                <li><strong>Agency Name:</strong> {{ $user->A_Name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $user->A_Email ?? 'N/A' }}</li>
            @elseif($userType === 'mcmc')
                <li><strong>Account Type:</strong> MCMC Staff</li>
                <li><strong>Name:</strong> {{ $user->M_Name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $user->M_Email ?? 'N/A' }}</li>
            @endif
        </ul>

        <p>If you continue to have problems, please contact our technical support team.</p>

        <p>Best regards,<br>
        <strong>MCMC System Team</strong></p>

        <div class="footer">
            <p><strong>Malaysian Communications and Multimedia Commission (MCMC)</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>For technical support, please contact the MCMC IT Department.</p>
        </div>
    </div>
</body>
</html>