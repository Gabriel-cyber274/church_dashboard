<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Reminder - {{ $program->name }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Merriweather:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .email-container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 50px 40px;
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

        .header-content {
            position: relative;
            z-index: 1;
        }

        .reminder-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .header-title {
            font-family: 'Merriweather', serif;
            font-size: 36px;
            font-weight: 700;
            color: white;
            margin: 0 0 15px 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 400;
        }

        .content {
            padding: 50px 40px;
            background: white;
        }

        .greeting {
            font-family: 'Merriweather', serif;
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 40px;
            line-height: 1.4;
        }

        .program-name {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .flier-container {
            margin: 40px 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .flier-container::before {
            content: '🎉 Program Flier';
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(79, 70, 229, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
        }

        .flier {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .flier:hover {
            transform: scale(1.02);
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .info-card-icon {
            font-size: 32px;
            margin-bottom: 20px;
            display: block;
        }

        .info-card-title {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-card-value {
            font-size: 20px;
            color: #1f2937;
            font-weight: 600;
            line-height: 1.4;
        }

        .countdown-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
            border: none;
        }

        .countdown-card .info-card-value {
            font-size: 32px;
            color: #92400e;
        }

        .countdown-number {
            font-size: 48px;
            font-weight: 700;
            color: #92400e;
            line-height: 1;
            margin-bottom: 5px;
        }

        .countdown-text {
            font-size: 14px;
            color: #92400e;
            opacity: 0.9;
        }

        .program-description {
            background: #f9fafb;
            border-radius: 16px;
            padding: 30px;
            margin: 40px 0;
            border-left: 4px solid #4f46e5;
        }

        .description-title {
            font-family: 'Merriweather', serif;
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .description-text {
            color: #6b7280;
            line-height: 1.7;
            margin: 0;
        }

        .actions {
            text-align: center;
            margin-top: 50px;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            border: none;
            cursor: pointer;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(79, 70, 229, 0.5);
        }

        .prayer-section {
            text-align: center;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #e5e7eb;
        }

        .prayer-text {
            font-family: 'Merriweather', serif;
            font-size: 20px;
            color: #4f46e5;
            font-style: italic;
            margin: 0;
            line-height: 1.6;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
        }

        .signature-text {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .church-name {
            font-family: 'Merriweather', serif;
            font-size: 24px;
            color: #1f2937;
            font-weight: 700;
        }

        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 25px 0;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: white;
        }

        .copyright {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 768px) {
            .email-container {
                margin: 20px;
                border-radius: 20px;
            }

            .header {
                padding: 40px 25px;
            }

            .header-title {
                font-size: 28px;
            }

            .content {
                padding: 40px 25px;
            }

            .info-cards {
                grid-template-columns: 1fr;
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }

            .countdown-number {
                font-size: 36px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 24px;
            }

            .greeting {
                font-size: 22px;
            }

            .cta-button {
                padding: 15px 30px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Church Logo" class="logo">
            </div>
            <div class="header-content">
                <div class="reminder-badge">Friendly Reminder</div>
                <h1 class="header-title">Program Announcement</h1>
                <p class="header-subtitle">Mark your calendar for this special event</p>
            </div>
        </div>

        <div class="content">
            <h2 class="greeting">
                Dear <span style="color: #4f46e5;">{{ $member->full_name }}</span>,
            </h2>

            <p style="font-size: 18px; color: #4b5563; line-height: 1.7; margin-bottom: 30px;">
                We're excited to remind you about our upcoming program
                <strong class="program-name">{{ $program->name }}</strong>.
                This is going to be a special time of fellowship, worship, and growth.
            </p>

            @if ($program->flier_url)
                <div class="flier-container">
                    <img src="{{ $program->flier_url }}" alt="Program Flier" class="flier">
                </div>
            @endif

            <div class="info-cards">
                <div class="info-card">
                    <span class="info-card-icon">📍</span>
                    <div class="info-card-title">Location</div>
                    <div class="info-card-value">{{ $program->location }}</div>
                </div>

                <div class="info-card">
                    <span class="info-card-icon">📅</span>
                    <div class="info-card-title">Date & Time</div>
                    <div class="info-card-value">
                        {{ \Carbon\Carbon::parse($program->start_date)->format('F j, Y') }}
                        @if ($program->start_time)
                            <br><span style="font-size: 16px; color: #6b7280;">{{ $program->start_time }}</span>
                        @endif
                    </div>
                </div>

                <div class="info-card countdown-card">
                    <span class="info-card-icon">⏰</span>
                    <div class="info-card-title">Countdown</div>
                    <div class="countdown-number">{{ $daysLeft }}</div>
                    <div class="countdown-text">Day{{ $daysLeft != 1 ? 's' : '' }} to go!</div>
                </div>
            </div>

            @if ($program->description)
                <div class="program-description">
                    <h3 class="description-title">About This Program</h3>
                    <p class="description-text">{{ $program->description }}</p>
                </div>
            @endif

            <div class="prayer-section">
                <p class="prayer-text">
                    "For where two or three gather in my name, there am I with them."
                    <br><strong style="color: #1f2937; font-weight: 400;">— Matthew 18:20</strong>
                </p>
            </div>

            <div class="signature">
                <p class="signature-text">Looking forward to seeing you there!</p>
                <p class="signature-text">
                    God bless you,<br>
                    <span class="church-name">{{ config('app.name') }}</span>
                </p>
            </div>
        </div>

        <div class="footer">

            <div class="copyright">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                <br>This is an automated reminder from your church management system.
            </div>
        </div>
    </div>

    <script>
        // Add subtle animation to countdown
        document.addEventListener('DOMContentLoaded', function() {
            const flierImg = document.querySelector('.flier');
            if (flierImg) {
                flierImg.onerror = function() {
                    const container = this.closest('.flier-container');
                    container.innerHTML = `
                        <div class="no-flier-content">
                            <div class="no-flier-icon">📋</div>
                            <p class="no-flier-text">No flier available for this program</p>
                            <p class="no-flier-subtext">Check below for program details</p>
                        </div>
                    `;
                    container.classList.remove('has-flier');
                    container.classList.add('no-flier');
                };
            }

            const countdownNumber = document.querySelector('.countdown-number');
            if (countdownNumber) {
                const days = parseInt(countdownNumber.textContent);
                if (days <= 3) {
                    countdownNumber.style.animation = 'pulse 2s infinite';
                }
            }
        });
    </script>
</body>

</html>
