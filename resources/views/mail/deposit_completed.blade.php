<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Completed</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Inter:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.8;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 1;
            margin-bottom: 25px;
        }

        .logo {
            max-width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .header-title {
            font-family: 'Crimson Text', serif;
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-family: 'Crimson Text', serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 50px 40px;
            background: white;
            position: relative;
        }

        .content::before {
            content: "✓";
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #10b981;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .greeting {
            font-family: 'Crimson Text', serif;
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            line-height: 1.4;
        }

        .message {
            font-size: 18px;
            color: #4a5568;
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .deposit-details {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 30px;
            border-radius: 16px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #0ea5e9;
        }

        .deposit-amount {
            font-family: 'Crimson Text', serif;
            font-size: 42px;
            font-weight: 700;
            color: #059669;
            margin: 15px 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .deposit-label {
            font-size: 16px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .program-name {
            font-family: 'Crimson Text', serif;
            font-size: 24px;
            color: #1e40af;
            margin: 15px 0;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .gratitude-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 30px;
            border-radius: 16px;
            margin: 40px 0;
            text-align: center;
            border-left: 6px solid #d97706;
        }

        .gratitude-text {
            font-family: 'Crimson Text', serif;
            font-size: 20px;
            color: #92400e;
            line-height: 1.6;
            margin: 0;
        }

        .bible-verse {
            font-family: 'Crimson Text', serif;
            font-size: 20px;
            font-style: italic;
            color: #1e40af;
            text-align: center;
            margin: 40px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #edf2f7 100%);
            border-radius: 12px;
            border-left: 4px solid #1e40af;
            line-height: 1.6;
        }

        .signature {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }

        .signature-name {
            font-family: 'Crimson Text', serif;
            font-size: 24px;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 16px;
            color: #718096;
            margin-top: 0;
        }

        .footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            padding: 30px 40px;
            text-align: center;
            color: white;
        }

        .church-name {
            font-family: 'Crimson Text', serif;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
        }

        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin: 10px 0;
        }

        .footer-verse {
            font-style: italic;
            margin-top: 20px;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .heart {
            color: #ff6b6b;
            display: inline-block;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        @media (max-width: 640px) {
            .email-container {
                margin: 20px;
                border-radius: 16px;
            }

            .header {
                padding: 40px 20px;
            }

            .content {
                padding: 40px 25px;
            }

            .greeting {
                font-size: 24px;
            }

            .message {
                font-size: 16px;
            }

            .deposit-amount {
                font-size: 36px;
            }

            .deposit-details {
                padding: 20px;
            }

            .bible-verse {
                font-size: 18px;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="logo-container">
                <!-- Logo would be inserted here -->
                <img src="{{ $logo }}" alt="Church Logo" class="logo">
            </div>
            <h1 class="header-title">Deposit Completed</h1>
            <div class="header-subtitle">Thank you for your contribution</div>
        </div>

        <div class="content">
            <h2 class="greeting">Dear {{ $member->full_name }},</h2>

            <div class="message">
                We're pleased to inform you that your deposit has been successfully processed and marked as completed.
            </div>

            <div class="deposit-details">
                <div class="deposit-label">Deposit Amount</div>
                <div class="deposit-amount">{{ number_format($deposit->amount, 2) }}</div>

                <div class="deposit-label">For Program</div>
                <div class="program-name">{{ $deposit->program?->name ?? 'Your Program' }}</div>

                <div class="status-badge">COMPLETED</div>
            </div>

            <div class="gratitude-section">
                <p class="gratitude-text">
                    Thank you for your generous contribution. We appreciate your support and commitment
                    to our mission and programs. Your participation helps us continue our important work
                    and make a meaningful difference in our community.
                </p>
            </div>

            <div class="bible-verse">
                "Each of you should give what you have decided in your heart to give,
                not reluctantly or under compulsion, for God loves a cheerful giver."<br>
                <strong>— 2 Corinthians 9:7</strong>
            </div>

            <div class="signature">
                <div class="signature-name">With gratitude,</div>
                <div class="signature-title">Your {{ config('app.name') }} Family</div>
            </div>
        </div>

        <div class="footer">
            <div class="church-name">{{ config('app.name') }}</div>
            <p class="footer-text">
                "Give, and it will be given to you. A good measure, pressed down,
                shaken together and running over, will be poured into your lap."<br>
                <strong class="footer-verse">— Luke 6:38</strong>
            </p>
            <p class="footer-text">
                Made with <span class="heart">❤️</span> by your church family
            </p>
        </div>
    </div>
</body>

</html>
