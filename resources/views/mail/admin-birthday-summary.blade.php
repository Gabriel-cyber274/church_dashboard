<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Celebrants - {{ $date }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f6f9fc 0%, #edf2f7 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.15' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.4;
        }

        .logo-container {
            position: relative;
            z-index: 1;
            margin-bottom: 25px;
        }

        .logo {
            max-width: 100px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 700;
            color: white;
            margin: 0 0 15px 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 400;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .date-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .content {
            padding: 50px 40px;
            background: white;
        }

        .celebrants-count {
            text-align: center;
            margin-bottom: 40px;
        }

        .count-card {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            padding: 20px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(250, 177, 160, 0.3);
        }

        .count-number {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #2d3436;
            line-height: 1;
        }

        .count-text {
            font-size: 18px;
            color: #636e72;
            font-weight: 600;
            text-align: left;
        }

        .count-text span {
            display: block;
            font-size: 14px;
            color: #b2bec3;
            font-weight: 400;
            margin-top: 5px;
        }

        .no-celebrants {
            text-align: center;
            padding: 60px 40px;
        }

        .no-celebrants-icon {
            font-size: 64px;
            margin-bottom: 25px;
            opacity: 0.3;
        }

        .no-celebrants-text {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #a4b0be;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .no-celebrants-subtext {
            font-size: 16px;
            color: #747d8c;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .celebrants-table-container {
            overflow-x: auto;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .celebrants-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }

        .celebrants-table thead {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }

        .celebrants-table th {
            padding: 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: none;
        }

        .celebrants-table th:first-child {
            border-top-left-radius: 16px;
        }

        .celebrants-table th:last-child {
            border-top-right-radius: 16px;
        }

        .celebrants-table td {
            padding: 20px;
            border-bottom: 1px solid #f1f2f6;
            vertical-align: middle;
            color: #2d3748;
            font-size: 15px;
        }

        .celebrants-table tbody tr {
            transition: all 0.3s ease;
        }

        .celebrants-table tbody tr:hover {
            background: linear-gradient(90deg, #f8f9fa 0%, #f1f2f6 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .member-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
            color: white;
            border-radius: 50%;
            font-weight: 600;
            margin-right: 15px;
        }

        .member-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 16px;
        }

        .member-email {
            color: #718096;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .member-email:hover {
            color: #667eea;
        }

        .email-icon {
            margin-right: 8px;
            color: #a0aec0;
        }

        .actions {
            text-align: center;
            margin-top: 50px;
            padding-top: 40px;
            border-top: 1px solid #edf2f7;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
        }

        .footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .church-name {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
        }

        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin: 15px 0;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 25px;
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
                font-size: 32px;
            }

            .content {
                padding: 40px 25px;
            }

            .count-card {
                padding: 15px 25px;
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .count-number {
                font-size: 36px;
            }

            .celebrants-table th,
            .celebrants-table td {
                padding: 15px;
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 28px;
            }

            .celebrants-table {
                font-size: 14px;
            }

            .celebrants-table th,
            .celebrants-table td {
                padding: 12px;
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
                <h1 class="header-title">🎉 Birthday Celebrants</h1>
                <div class="header-subtitle">
                    Celebrating God's Gifts • <span class="date-badge">{{ $date }}</span>
                </div>
            </div>
        </div>

        <div class="content">
            @if ($members->count())
                <div class="celebrants-count">
                    <div class="count-card">
                        <div class="count-number">{{ $members->count() }}</div>
                        <div class="count-text">
                            Member{{ $members->count() > 1 ? 's' : '' }} Celebrating Today
                            <span>Let's send them our love and prayers</span>
                        </div>
                    </div>
                </div>

                <div class="celebrants-table-container">
                    <table class="celebrants-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 45%;">Member</th>
                                <th style="width: 50%;">Contact Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $index => $member)
                                <tr>
                                    <td style="font-weight: 600; color: #718096;">{{ $index + 1 }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div class="member-avatar">
                                                {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="member-name">{{ $member->full_name }}</div>
                                                <div style="font-size: 13px; color: #a0aec0; margin-top: 2px;">
                                                    🎂 Birthday Celebrant
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <a href="mailto:{{ $member->email }}" class="member-email">
                                                <span class="email-icon">✉️</span>
                                                {{ $member->email }}
                                            </a>
                                            @if ($member->phone_number)
                                                <div style="font-size: 14px; color: #718096;">
                                                    <span style="color: #a0aec0;">📱</span>
                                                    {{ $member->phone_number }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-celebrants">
                    <div class="no-celebrants-icon">🎂</div>
                    <h2 class="no-celebrants-text">No Birthdays Today</h2>
                    <p class="no-celebrants-subtext">
                        There are no members celebrating their birthday on {{ $date }}.
                        However, every day is worth celebrating God's grace and blessings!
                    </p>
                </div>
            @endif
        </div>

        <div class="footer">
            <div class="church-name">{{ config('app.name') }}</div>
            <p class="footer-text">
                "This is the day that the LORD has made; let us rejoice and be glad in it."
                <br><strong>— Psalm 118:24</strong>
            </p>

            <div class="copyright">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                <br>This email was automatically generated by the church management system.
            </div>
        </div>
    </div>
</body>

</html>
