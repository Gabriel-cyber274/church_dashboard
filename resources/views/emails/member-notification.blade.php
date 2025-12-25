<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .email-container {
            max-width: 600px;
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
        }

        .logo {
            max-width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            margin-bottom: 20px;
        }

        .notification-badge {
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
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 600;
            color: white;
            margin: 0 0 10px 0;
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
            position: relative;
        }

        .message-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 40px;
            margin: 30px 0;
            border-left: 4px solid #4f46e5;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .message-content {
            font-size: 16px;
            line-height: 1.8;
            color: #374151;
        }

        .message-content h1,
        .message-content h2,
        .message-content h3 {
            font-family: 'Playfair Display', serif;
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .message-content h1 {
            font-size: 24px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .message-content h2 {
            font-size: 20px;
        }

        .message-content h3 {
            font-size: 18px;
        }

        .message-content p {
            margin-bottom: 20px;
        }

        .message-content ul,
        .message-content ol {
            margin: 15px 0;
            padding-left: 20px;
        }

        .message-content li {
            margin-bottom: 8px;
        }

        .message-content strong {
            color: #1f2937;
            font-weight: 600;
        }

        .message-content em {
            color: #6b7280;
            font-style: italic;
        }

        .message-content a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid #c7d2fe;
            transition: all 0.3s ease;
        }

        .message-content a:hover {
            color: #3730a3;
            border-bottom-color: #4f46e5;
        }

        .message-content code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #7c3aed;
        }

        .message-content blockquote {
            border-left: 4px solid #10b981;
            margin: 25px 0;
            padding-left: 20px;
            font-style: italic;
            color: #065f46;
            background: #ecfdf5;
            padding: 20px;
            border-radius: 8px;
        }

        .meta-info {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .meta-row:last-child {
            border-bottom: none;
        }

        .meta-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }

        .meta-value {
            color: #1f2937;
            font-weight: 500;
        }

        .subject-display {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px dashed #e5e7eb;
        }

        .subject-text {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1f2937;
            margin: 0;
            font-weight: 600;
            line-height: 1.4;
        }

        .timestamp {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .quick-actions {
            text-align: center;
            margin: 40px 0;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.3);
            border: none;
            cursor: pointer;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        }

        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            padding: 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .church-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
            position: relative;
            z-index: 1;
        }

        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin: 15px 0;
            position: relative;
            z-index: 1;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 25px 0;
            position: relative;
            z-index: 1;
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
            position: relative;
            z-index: 1;
        }

        .notification-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
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

            .message-container {
                padding: 30px 25px;
            }

            .subject-text {
                font-size: 24px;
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }

            .meta-row {
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 24px;
            }

            .subject-text {
                font-size: 20px;
            }

            .action-button {
                padding: 12px 25px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="logo"
                    onerror="this.style.display='none'">
            </div>
            <div class="notification-badge">🔔 Notification</div>
            <h1 class="header-title">{{ config('app.name') }}</h1>
            <p class="header-subtitle">Church Management System</p>
        </div>

        <div class="content">
            <div class="subject-display">
                <div class="notification-icon">📧</div>
                <h2 class="subject-text">{{ $subject ?? 'Important Announcement' }}</h2>
                <div class="timestamp">
                    <span>📅</span>
                    <span>{{ now()->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <div class="meta-info">
                <div class="meta-row">
                    <span class="meta-label">From:</span>
                    <span class="meta-value">{{ config('app.name') }} Administration</span>
                </div>
            </div>

            <div class="message-container">
                <div class="message-content">
                    {!! nl2br(e($content)) !!}
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="church-name">{{ config('app.name') }}</div>
            <p class="footer-text">
                This is an automated notification from {{ config('app.name') }} Church Management System.
                Please do not reply to this email.
            </p>

            <div class="copyright">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email was sent to you as part of our church communications.
            </div>
        </div>
    </div>

    <script>
        // Add animation for notification icon
        document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.querySelector('.notification-icon');
            if (notificationIcon) {
                setTimeout(() => {
                    notificationIcon.style.transform = 'scale(1.1)';
                    notificationIcon.style.transition = 'transform 0.3s ease';
                }, 300);

                setTimeout(() => {
                    notificationIcon.style.transform = 'scale(1)';
                }, 600);
            }

            // Make all external links open in new tab
            const messageContent = document.querySelector('.message-content');
            if (messageContent) {
                const links = messageContent.querySelectorAll('a');
                links.forEach(link => {
                    if (link.href && !link.href.includes(window.location.hostname)) {
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                    }
                });
            }
        });
    </script>
</body>

</html>
