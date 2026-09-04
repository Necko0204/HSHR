<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/security.php';
hshr_security_headers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Application Submitted</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e0e0e0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            animation: fadeIn 1s ease-in-out;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 30px;
            animation: bounceIn 1s ease-in-out;
        }
        h1 {
            color: #d32f2f;
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease-in-out;
        }
        p {
            color: #555;
            font-size: 1.2em;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-in-out;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin-top: 30px;
            text-decoration: none;
            background-color: #d32f2f;
            color: white;
            border-radius: 50px;
            transition: 0.3s;
            font-size: 1.2em;
            animation: fadeInUp 1s ease-in-out;
        }
        .btn:hover {
            background-color: #b71c1c;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounceIn {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images\asdasdasd123123123123123.jpg" alt="Company Logo" class="logo"> <!-- Update with your logo path -->
        <h1>Application Submitted Successfully</h1>
        <p>Thank you for your application! Your submission has been received.</p>
        <p>Our HR team will review your application and contact you within 2-3 business days.</p>
        <a href="#" id="closePageBtn" class="btn">Close Page</a>
    </div>
    <script>
        document.getElementById('closePageBtn').addEventListener('click', function() {
            window.close();
        });
    </script>
</body>
</html>
