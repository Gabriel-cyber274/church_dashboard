<!DOCTYPE html>
<html>

<head>
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }

        .content {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="logo">
        <h2>{{ config('app.name') }}</h2>
    </div>

    <div class="content">
        {!! nl2br(e($content)) !!}
    </div>

    <div class="footer">
        <p>This email was sent from {{ config('app.name') }}.</p>
        <p>Please do not reply to this automated message.</p>
    </div>
</body>

</html>
