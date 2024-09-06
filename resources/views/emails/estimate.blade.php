<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Offer Details</title>
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
        .provider-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .provider-info img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .provider-info div {
            line-height: 1.6;
        }
        .provider-info .provider-name {
            font-size: 18px;
            font-weight: bold;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .details .cost-time {
            display: flex;
            justify-content: space-between;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
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
        <h1>Service Offer Details</h1>
    </header>
    <div class="container">
        <div class="content">
            <div class="provider-info">
                <img src="{{ $estimate->provider->getFirstMediaUrl('user') }}" alt="Provider Image">
                <div>
                    <p class="provider-name">{{ $estimate->provider->name }}</p>
                </div>
            </div>
            <h2>Service: {{ $estimate->request->service->profession->name }}</h2>
            <div class="details">
                <p><strong>Cost:</strong> ${{ $estimate->price }}</p>
                <p><strong>Time Required:</strong> {{ $estimate->estimated_time }}</p>
                @if ( $estimate->addational_notes )
                    <p><strong>Additional Notes:</strong> {{ $estimate->addational_notes }}</p>
                @endif
            </div>
            <a href="{{ $estimate->request->url }}" class="button">View Offer Details</a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</body>
</html>
