<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Agency Login Credentials</title>
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
            background-color: #3b82f6;
            color: white;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 30px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .credentials-box {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }
        .credential-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background-color: #f3f4f6;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            word-break: break-all;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 Welcome to MCMC System</h1>
            <p>Your Agency Account Has Been Created</p>
        </div>

        <h2>Hello {{ $agency->A_Name }},</h2>
        
        <p>Your agency has been successfully registered in the MCMC Inquiry Management System. Below are your login credentials:</p>

        <div class="credentials-box">
            <h3 style="margin-top: 0; color: #374151;">🔐 Login Credentials</h3>
            
            <div class="credential-item">
                <div class="credential-label">Agency ID:</div>
                <div class="credential-value">{{ $agency->A_ID }}</div>
            </div>
            
            <div class="credential-item">
                <div class="credential-label">Email Address:</div>
                <div class="credential-value">{{ $agency->A_Email }}</div>
            </div>
            
            <div class="credential-item">
                <div class="credential-label">Username:</div>
                <div class="credential-value">{{ $agency->A_userName }}</div>
            </div>
            
            <div class="credential-item">
                <div class="credential-label">Password:</div>
                <div class="credential-value">{{ $password }}</div>
            </div>
        </div>

        <div class="warning">
            <span class="warning-icon">⚠️</span>
            <strong>Important Security Notice:</strong>
            <ul style="margin: 10px 0;">
                <li>Please change your password after your first login</li>
                <li>Keep your credentials confidential and secure</li>
                <li>Do not share your login details with unauthorized personnel</li>
                <li>If you suspect any security breach, contact MCMC immediately</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">🚀 Login to Your Account</a>
        </div>

        <h3>Getting Started:</h3>
        <ol>
            <li><strong>Login:</strong> Use the credentials above to access your account</li>
            <li><strong>Complete Profile:</strong> Update your agency profile information</li>
            <li><strong>Set New Password:</strong> Change your password for security</li>
            <li><strong>Start Managing:</strong> Begin handling assigned inquiries</li>
        </ol>

        <h3>Your Agency Information:</h3>
        <ul>
            <li><strong>Agency Name:</strong> {{ $agency->A_Name }}</li>
            <li><strong>Category:</strong> {{ $agency->A_Category }}</li>
            <li><strong>Email:</strong> {{ $agency->A_Email }}</li>
            <li><strong>Phone:</strong> {{ $agency->A_PhoneNum }}</li>
        </ul>

        <div class="footer">
            <p><strong>Malaysian Communications and Multimedia Commission (MCMC)</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>For technical support, please contact the MCMC IT Department.</p>
        </div>
    </div>
</body>
</html>