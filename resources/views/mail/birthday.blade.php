<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Birthday!</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            content: "🎉";
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        .bible-verse {
            font-family: 'Crimson Text', serif;
            font-size: 20px;
            font-style: italic;
            color: #667eea;
            text-align: center;
            margin: 40px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #edf2f7 100%);
            border-radius: 12px;
            border-left: 4px solid #667eea;
            line-height: 1.6;
        }

        .prayer {
            background: linear-gradient(135deg, #fff9db 0%, #ffec99 100%);
            padding: 30px;
            border-radius: 16px;
            margin: 40px 0;
            text-align: center;
            border: 2px dashed #ffd43b;
        }

        .prayer-text {
            font-family: 'Crimson Text', serif;
            font-size: 20px;
            color: #5c3c00;
            line-height: 1.6;
            margin: 0;
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
                <img src="{{ $logo }}" alt="Church Logo" class="logo">
            </div>
            <h1 class="header-title">Happy Birthday!</h1>
            <div class="header-subtitle">Celebrating God's Gift of Life</div>
        </div>

        <div class="content">
            <h2 class="greeting">Dear {{ $member->full_name }},</h2>

            <div class="message">
                Today, we join heaven in celebrating the beautiful gift of your life!
                On this special day, we thank God for the wonderful person you are and
                the blessing you've been to our church family.
            </div>

            <div class="bible-verse">
                "For you created my inmost being;<br>
                you knit me together in my mother's womb.<br>
                I praise you because I am fearfully and wonderfully made;<br>
                your works are wonderful, I know that full well."<br>
                <strong>— Psalm 139:13-14</strong>
            </div>

            <div class="prayer">
                <p class="prayer-text">
                    We pray that the Lord showers you with abundant blessings,
                    fills your heart with peace and joy, grants you excellent health,
                    and surrounds you with His unending grace throughout the coming year.
                </p>
            </div>

            <div class="message">
                Thank you for being a cherished member of our church family.
                Your presence, gifts, and contributions are deeply valued and appreciated.
            </div>

            <div class="signature">
                <div class="signature-name">With love and prayers</div>
                <div class="signature-title">Your {{ config('app.name') }} Family</div>
            </div>
        </div>

        <div class="footer">
            <div class="church-name">{{ config('app.name') }}</div>
            <p class="footer-text">
                "For I know the plans I have for you," declares the Lord,<br>
                "plans to prosper you and not to harm you,<br>
                plans to give you hope and a future."<br>
                <strong class="footer-verse">— Jeremiah 29:11</strong>
            </p>
            <p class="footer-text">
                Made with <span class="heart">❤️</span> by your church family
            </p>
        </div>
    </div>
</body>

</html>
