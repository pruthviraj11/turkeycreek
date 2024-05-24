<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Service</title>
    <style>
        .container {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: left;
            max-width: 384px;
            margin: auto;
            margin-top: 70px;
            height: auto; /* removed fixed height */
        }
        .header {
            background-color: #fff;
            padding: 0 15px;
            text-align: center;
        }
        .header img {
            display: inline-block;
            width: auto;
            height: 100px;
            margin: 15px 0; /* added margin */
        }
        .content {
            text-align: left; /* adjusted alignment */
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 0.9em;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        /* Additional Styles */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 34px;
        }
        a.button {
            display: inline-block;
            background-color: #F47D28;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 28px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="asset('imgs/Logo.jpg')" alt="Logo">
        </div>
        <h1>Hello, {{ $name }}!</h1>
        <hr>
        <div class="content">
            <p>Thank you for registering with us. We're glad to have you on board.</p>
            <p>If you have any questions, feel free to reach out to us at <a href="mailto:support@example.com">support@example.com</a>.</p>
        </div>
        <div class="footer">
            <p>Best Regards,<br>Our Team</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
