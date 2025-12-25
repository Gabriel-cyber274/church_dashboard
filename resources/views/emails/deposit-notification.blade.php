<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contribution Received</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 2px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .header {
            background-color: #2c3e50;
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #34495e;
        }

        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .header h1 {
            color: white;
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .content {
            padding: 40px 30px;
        }

        .type-badge {
            display: inline-block;
            padding: 8px 20px;
            background-color: #34495e;
            color: white;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        h2 {
            color: #2c3e50;
            font-size: 22px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .amount {
            font-size: 42px;
            font-weight: 700;
            color: #27ae60;
            text-align: center;
            margin: 30px 0;
            letter-spacing: -0.5px;
        }

        .info-box {
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #2c3e50;
            padding: 25px;
            margin: 25px 0;
            border-radius: 2px;
        }

        .info-box h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #666666;
            font-size: 14px;
        }

        .detail-value {
            color: #333333;
            font-weight: 500;
            text-align: right;
        }

        .view-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white !important;
            text-decoration: none;
            border-radius: 3px;
            font-weight: 600;
            font-size: 13px;
            transition: background-color 0.2s ease;
        }

        .view-link:hover {
            background-color: #34495e;
        }

        .footer {
            background-color: #fafafa;
            padding: 30px 20px;
            text-align: center;
            color: #666666;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            margin: 8px 0;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                border-radius: 12px;
            }

            .content {
                padding: 30px 20px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .amount {
                font-size: 36px;
            }

            .detail-row {
                flex-direction: column;
                gap: 8px;
            }

            .detail-label,
            .detail-value {
                width: 100%;
                text-align: left;
            }

            .info-box {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if (file_exists($logoPath))
                <img src="{{ $message->embed($logoPath) }}" alt="Logo" class="logo">
            @else
                <img src="{{ $logoUrl }}" alt="Logo" class="logo">
            @endif
            <h1>New Contribution Received</h1>
        </div>

        <div class="content">
            <div class="type-badge">
                {{ $typeLabel }} Contribution
            </div>

            <h2>{{ $itemName }}</h2>

            <div class="amount">
                ₦{{ number_format($deposit->amount, 2) }}
            </div>

            <div class="info-box">
                <h3>Contribution Details</h3>

                <div class="detail-row">
                    <div class="detail-label">Transaction Type:</div>
                    <div class="detail-value">
                        {{ $isPledge ? 'Pledge Payment' : 'Direct Contribution' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Date & Time:</div>
                    <div class="detail-value">
                        {{ $deposit->deposit_date->format('F j, Y, g:i a') }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">View Details:</div>
                    <div class="detail-value">
                        <a href="{{ url('admin/deposits/' . $deposit->id) }}" class="view-link">View Transaction</a>
                    </div>
                </div>

                @if (!is_null($pledge))
                    <div class="detail-row">
                        <div class="detail-label">Pledge Details:</div>
                        <div class="detail-value">
                            <a href="{{ url('admin/pledges/' . $pledge->id) }}" class="view-link">View Pledge</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="info-box">
                <h3>Donor Information</h3>

                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value">{{ $member->first_name }} {{ $member->last_name }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Phone Number:</div>
                    <div class="detail-value">{{ $member->phone_number }}</div>
                </div>

                @if ($member->email)
                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $member->email }}</div>
                    </div>
                @endif
            </div>

            <div class="info-box">
                <h3>Payment Information</h3>

                <div class="detail-row">
                    <div class="detail-label">Payment Method:</div>
                    <div class="detail-value">Online Portal</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Description:</div>
                    <div class="detail-value" style="font-size: 13px; line-height: 1.5;">
                        {{ $deposit->description }}
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>This is an automated notification from {{ config('app.name') }}</strong></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
