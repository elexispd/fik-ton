<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
        }

        p {
            color: #666666;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #777777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://via.placeholder.com/200x50" alt="Company Logo" class="logo">
        <h1>Thank You for Signing Up!</h1>
        <p>Hi [User],</p>
        <p>Welcome to our community! We're excited to have you on board. To get started, please click the button below to confirm your email address:</p>

        <a href="[Confirmation Link]" class="cta-button">Confirm Email</a>

        <div class="footer">
            <p>If you did not sign up or request this action, please ignore this email. Your account security is important to us.</p>
            <p>This email was sent to [User's Email]. You received this email because you signed up on our website.</p>
        </div>
    </div>
</body>

</html>
