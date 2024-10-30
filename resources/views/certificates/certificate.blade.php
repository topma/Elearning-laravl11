<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .certificate {
            border: 5px solid gold;
            width: 800px;
            padding: 20px;
            background-color: white;
            text-align: center;
        }
        .company-logo {
            max-width: 150px;
            margin: 0 auto;
        }
        .title {
            font-size: 36px;
            margin: 20px 0;
        }
        .name {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
        }
        .course {
            font-size: 24px;
            margin: 20px 0;
        }
        .date {
            font-size: 18px;
            margin: 20px 0;
        }
        .signature {
            margin-top: 50px;
            font-size: 18px;
        }
        .company-name {
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div style="position: relative; width: 100%; height: 100%;">
    <!-- Background Image -->
    <img src="{{ asset('images/certificate_back.png') }}" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1;">

    <!-- Logo -->
    <div style="text-align: center; margin-top: 30px;">
        <!-- <img src="{{ asset('images/kdh_logo_big.png') }}" style="height: 70px;"> -->
    </div>

    <!-- Certificate Title -->
    <h1 style="text-align: center; font-size: 40px; font-family: 'Cormorant Garamond', serif; margin-top: 20px;">
        Certificate of Completion
    </h1>

    <!-- Student Name and Course Info -->
    <p style="text-align: center; font-size: 20px; margin-top: 30px;">
        This certifies that
    </p>
    <h2 style="text-align: center; font-size: 28px; font-family: 'Playfair Display', serif; font-weight: bold;">
        Maxwell Akinyooye
    </h2>
    <p style="text-align: center; font-size: 18px; margin-top: 10px;">
        has successfully completed the course
    </p>
    <h2 style="text-align: center; font-size: 24px; font-family: 'Playfair Display', serif;">
        Freelance Bootcamp
    </h2>
    <p style="text-align: center; font-size: 16px; margin-top: 5px;">
        on 24th October 2024
    </p>

    <!-- Signature and Seal -->
    <div style="text-align: center; margin-top: 50px;">
        <!-- <img src="{{ asset('images/signature.png') }}" style="height: 60px;"> -->
        <p style="margin-top: 0;">Miracle Peter</p>
        <p style="font-size: 14px;">Kings Digital Literacy Hub</p>
    </div>
</div>
</body>
</html>
