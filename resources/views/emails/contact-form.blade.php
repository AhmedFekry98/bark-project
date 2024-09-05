<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #ddd 3px solid;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        .content {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .content h2 {
            color: #333;
        }
        .content p {
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background: #333;
            color: #fff;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <header>
        <h1>Contact Form Submission</h1>
    </header>
    <div class="container">
        <div class="content">
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {{ $request->name }}</p>
            <p><strong>Email:</strong> {{ $request->email }}</p>
            <p><strong>Phone:</strong> {{ $request->phone }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $request->message }}</p>
        </div>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</body>
</html>
