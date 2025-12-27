<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if ($action === 'created')
            New Withdrawal Request
        @elseif ($action === 'updated')
            Withdrawal Updated
        @elseif ($action === 'status_updated')
            Withdrawal Status Updated
        @elseif ($action === 'restored')
            Withdrawal Restored
        @else
            Withdrawal Deleted
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
            background-color: #D32F2F;
            /* Changed to red for withdrawal theme */
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #B71C1C;
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
            background-color: #F44336;
            color: white;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .type-badge.status_updated {
            background-color: #2196F3;
        }

        .type-badge.restored {
            background-color: #FFA000;
        }

        .type-badge.deleted {
            background-color: #607D8B;
        }

        h2 {
            color: #D32F2F;
            font-size: 22px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .amount {
            font-size: 42px;
            font-weight: 700;
            color: #D32F2F;
            text-align: center;
            margin: 30px 0;
            letter-spacing: -0.5px;
        }

        .info-box {
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #D32F2F;
            padding: 25px;
            margin: 25px 0;
            border-radius: 2px;
        }

        .info-box.status_updated {
            border-left-color: #2196F3;
        }

        .info-box.restored {
            border-left-color: #FFA000;
        }

        .info-box.deleted {
            border-left-color: #607D8B;
        }

        .info-box h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #D32F2F;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .info-box.status_updated h3 {
            color: #2196F3;
        }

        .info-box.restored h3 {
            color: #FFA000;
        }

        .info-box.deleted h3 {
            color: #607D8B;
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
            background-color: #D32F2F;
            color: white !important;
            text-decoration: none;
            border-radius: 3px;
            font-weight: 600;
            font-size: 13px;
            transition: background-color 0.2s ease;
        }

        .view-link:hover {
            background-color: #F44336;
        }

        .view-link.status_updated {
            background-color: #2196F3;
        }

        .view-link.status_updated:hover {
            background-color: #42A5F5;
        }

        .view-link.restored {
            background-color: #FFA000;
        }

        .view-link.restored:hover {
            background-color: #FFB300;
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
            background-color: #D32F2F;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .changes-table.status_updated th {
            background-color: #2196F3;
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

        .status-updated-notice {
            background-color: #E3F2FD;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 2px;
            font-size: 14px;
            color: #0D47A1;
        }

        .restored-notice {
            background-color: #FFF3E0;
            border-left: 4px solid #FFA000;
            padding: 15px;
            margin: 20px 0;
            border-radius: 2px;
            font-size: 14px;
            color: #5D4037;
        }

        .deleted-notice {
            background-color: #ECEFF1;
            border-left: 4px solid #607D8B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 2px;
            font-size: 14px;
            color: #37474F;
        }

        .status-pending {
            color: #f39c12;
        }

        .status-approved {
            color: #2196F3;
        }

        .status-completed {
            color: #27ae60;
        }

        .status-rejected {
            color: #e74c3c;
        }

        .status-cancelled {
            color: #607D8B;
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
                    New Withdrawal Request
                @elseif ($action === 'updated')
                    Withdrawal Updated
                @elseif ($action === 'status_updated')
                    Withdrawal Status Updated
                @elseif ($action === 'restored')
                    Withdrawal Restored
                @else
                    Withdrawal {{ $action === 'force deleted' ? 'Permanently Deleted' : 'Deleted' }}
                @endif
            </h1>
        </div>

        <div class="content">
            <div
                class="type-badge {{ $action === 'deleted' || $action === 'force deleted' ? 'deleted' : ($action === 'restored' ? 'restored' : ($action === 'status_updated' ? 'status_updated' : '')) }}">
                @if ($action === 'created')
                    New Withdrawal
                @elseif ($action === 'updated')
                    Withdrawal Update
                @elseif ($action === 'status_updated')
                    Status Updated
                @elseif ($action === 'restored')
                    Restored
                @elseif ($action === 'force deleted')
                    Permanent Deletion
                @else
                    Deleted
                @endif
            </div>

            <h2>
                @if ($action === 'created')
                    A new withdrawal request has been created by the Finance team.
                @elseif ($action === 'updated')
                    A withdrawal has been updated by the Finance team.
                @elseif ($action === 'status_updated')
                    A withdrawal status has been updated by Admin.
                @elseif ($action === 'restored')
                    A withdrawal has been restored by the Finance team.
                @elseif ($action === 'force deleted')
                    A withdrawal has been permanently deleted by the Finance team.
                @else
                    A withdrawal has been deleted by the Finance team.
                @endif
            </h2>

            @if ($action === 'status_updated')
                <div class="status-updated-notice">
                    <strong>Important:</strong> This withdrawal status has been updated to
                    <strong class="status-{{ $withdrawal->status }}">{{ ucfirst($withdrawal->status) }}</strong>
                    by an Administrator.
                </div>
            @endif

            @if ($action === 'restored')
                <div class="restored-notice">
                    <strong>Note:</strong> This withdrawal has been restored and is now active again in the system.
                </div>
            @endif

            @if ($action === 'deleted' || $action === 'force deleted')
                <div class="deleted-notice">
                    <strong>Note:</strong> This withdrawal has been
                    @if ($action === 'force deleted')
                        <strong>permanently deleted</strong> from the system.
                    @else
                        deleted. It can be restored from the trash.
                    @endif
                </div>
            @endif

            @if (in_array($action, ['updated', 'status_updated']) && $changes && count($changes))
                <div class="info-box {{ $action === 'status_updated' ? 'status_updated' : '' }}">
                    <h3>Changes Made</h3>
                    <table class="changes-table {{ $action === 'status_updated' ? 'status_updated' : '' }}">
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
                                    <td>{{ $change['old'] ?? 'N/A' }}</td>
                                    <td>{{ $change['new'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="amount">
                ₦{{ number_format($withdrawal->amount, 2) }}
            </div>

            <div
                class="info-box {{ $action === 'deleted' || $action === 'force deleted' ? 'deleted' : ($action === 'restored' ? 'restored' : ($action === 'status_updated' ? 'status_updated' : '')) }}">
                <h3>Withdrawal Details</h3>

                <div class="detail-row">
                    <div class="detail-label">Withdrawal ID:</div>
                    <div class="detail-value">#{{ $withdrawal->id }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Program:</div>
                    <div class="detail-value">{{ $withdrawal->program->name ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Project:</div>
                    <div class="detail-value">{{ $withdrawal->project->name ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Withdrawal Date:</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('F d, Y') }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value status-{{ $withdrawal->status }}">
                        {{ ucfirst($withdrawal->status) }}
                    </div>
                </div>

                @if ($withdrawal->description)
                    <div class="detail-row">
                        <div class="detail-label">Description:</div>
                        <div class="detail-value" style="font-size: 13px; line-height: 1.5;">
                            {{ $withdrawal->description }}
                        </div>
                    </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Created At:</div>
                    <div class="detail-value">{{ $withdrawal->created_at->format('F d, Y h:i A') }}</div>
                </div>

                @if (in_array($action, ['updated', 'status_updated', 'restored']))
                    <div class="detail-row">
                        <div class="detail-label">Updated At:</div>
                        <div class="detail-value">{{ $withdrawal->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                @endif
            </div>

            <div style="text-align: center; margin-top: 30px;">
                @if ($action !== 'force deleted')
                    <a href="{{ url('admin/withdrawals/' . $withdrawal->id) }}"
                        class="view-link {{ $action === 'restored' ? 'restored' : ($action === 'status_updated' ? 'status_updated' : '') }}">
                        @if ($action === 'restored')
                            View Restored Withdrawal
                        @elseif ($action === 'status_updated')
                            View Updated Withdrawal
                        @else
                            View Withdrawal Details
                        @endif
                    </a>
                @else
                    <a href="{{ url('admin/withdrawals') }}" class="view-link">
                        View All Withdrawals
                    </a>
                @endif
            </div>
        </div>

        <div class="footer">
            <p><strong>This is an automated notification from {{ config('app.name') }}</strong></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
