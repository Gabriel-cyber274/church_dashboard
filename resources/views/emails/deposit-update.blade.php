<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if ($action === 'created')
            New Deposit Created
        @elseif ($action === 'updated')
            Deposit Updated
        @else
            Deposit Deleted
        @endif
    </title>
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
            margin-top: 10px;
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

        .type-badge.deleted {
            background-color: #e74c3c;
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

        .info-box.deleted {
            border-left-color: #e74c3c;
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
            flex: 1;
        }

        .detail-value {
            color: #333333;
            font-weight: 500;
            text-align: right;
            flex: 1;
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

        .changes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }

        .changes-table th {
            background-color: #2c3e50;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .changes-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #333333;
        }

        .changes-table tr:last-child td {
            border-bottom: none;
        }

        .changes-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .deleted-notice {
            background-color: #fdecea;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 2px;
            font-size: 14px;
            color: #721c24;
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

            .changes-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <h1>
                @if ($action === 'created')
                    New Deposit Created
                @elseif ($action === 'updated')
                    Deposit Updated
                @else
                    Deposit Deleted
                @endif
            </h1>
        </div>

        <div class="content">
            <div class="type-badge {{ $action === 'deleted' || $action === 'force deleted' ? 'deleted' : '' }}">
                @if ($action === 'created')
                    New
                @elseif ($action === 'updated')
                    Update
                @else
                    Deleted
                @endif
            </div>

            <h2>
                @if ($action === 'created')
                    A new deposit has been created by the Finance team.
                @elseif ($action === 'updated')
                    A deposit has been updated by the Finance team.
                @else
                    A deposit has been deleted by the Finance team.
                @endif
            </h2>

            @if ($action === 'deleted' || $action === 'force deleted')
                <div class="deleted-notice">
                    <strong>Note:</strong> This deposit has been removed from the system.
                </div>
            @endif

            @if ($action !== 'created' && $action !== 'deleted' && $action !== 'force deleted' && $changes && count($changes))
                <div class="info-box">
                    <h3>Changes Made</h3>
                    <table class="changes-table">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($changes as $field => $change)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                                    <td>{{ $change['old'] }}</td>
                                    <td>{{ $change['new'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="amount">
                {{ number_format($deposit->amount, 2) }}
            </div>

            <div class="info-box {{ $action === 'deleted' || $action === 'force deleted' ? 'deleted' : '' }}">
                <h3>Deposit Details</h3>

                <div class="detail-row">
                    <div class="detail-label">Deposit ID:</div>
                    <div class="detail-value">{{ $deposit->id }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Member:</div>
                    <div class="detail-value">{{ $deposit->member->name ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Program:</div>
                    <div class="detail-value">{{ $deposit->program->name ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Project:</div>
                    <div class="detail-value">{{ $deposit->project->name ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">{{ ucfirst($deposit->status) }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Deposit Date:</div>
                    <div class="detail-value">{{ $deposit->deposit_date->format('Y-m-d') }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Description:</div>
                    <div class="detail-value" style="font-size: 13px; line-height: 1.5;">
                        {{ $deposit->description }}
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                {{-- @if ($action !== 'deleted' && $action !== 'force deleted') --}}
                <a href="{{ url('admin/deposits/' . $deposit->id) }}" class="view-link">
                    View Deposit Details
                </a>
                {{-- @else
                    <a href="{{ url('admin/deposits') }}" class="view-link">
                        View All Deposits
                    </a>
                @endif --}}
            </div>
        </div>

        <div class="footer">
            <p><strong>This is an automated notification from {{ config('app.name') }}</strong></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
