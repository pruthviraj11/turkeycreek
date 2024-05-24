<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: auto;
            height: 100px;
        }

        .otp-section {
            text-align: center;
            margin-top: 20px;
        }

        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo">
            <img src="path/to/your/logo.png" alt="Logo">
        </div>
        <h2 style="text-align: center; margin-bottom: 20px;">Forgot Password OTP</h2>
        <hr>
        <div class="otp-section">
            {{-- <p>Your OTP for Turkey Creek is:</p>
            <p class="otp">{{ $otp }}</p> --}}
            <p style="font-size: 20px; text-align: center; margin-bottom: 10px;">Dear Customer,</p>
            <p style="font-size: 24px; text-align: center; margin-bottom: 20px;">Your One-Time Password (OTP) for
                accessing your account at Turkey Creek is:</p>
            <h2 style="font-size: 36px; text-align: center; margin-bottom: 30px;">{{ $otp }}</h2>
            <p style="font-size: 18px; text-align: center;">Please use this OTP to securely verify your identity and
                proceed with the desired action.</p>

        </div>
    </div>

</body>

</html>
