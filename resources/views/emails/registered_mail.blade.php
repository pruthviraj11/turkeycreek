<!DOCTYPE html>
<html>
<head>
    <title>New User Registration</title>
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
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content li {
            background-color: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-left: 5px solid #B0AC89;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 0.9em;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        /* Additional Styles */
        h2 {
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
            <img src="asset('imgs/TurkeyCreekLogo2.jpg')" alt="Logo">
        </div>
        <h2>Vip Membership Registration</h2>
        <hr>
        <div class="content">
            <ul>
                <li><strong>Name:</strong> {{ $name }}</li>
                <li><strong>Email:</strong> {{ $email }}</li>
                <li><strong>Mobile No:</strong> {{ $mobile_no }}</li>
                <li><strong>Address:</strong> {{ $address }}</li>
                <li><strong>Zip Code:</strong> {{ $zip_code }}</li>
            </ul>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
