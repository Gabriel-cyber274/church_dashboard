<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pledge Notification</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px;margin:auto;background:#fff;border:1px solid #ddd;padding:30px;">

        <h2 style="color:#2c3e50;">
            {{ $daysLeft === 0 ? 'Pledge Due Today' : 'Upcoming Pledge Reminder' }}
        </h2>

        <p>Member: <strong>{{ $member->full_name }}</strong></p>
        <p>Email: <strong>{{ $member->email }}</strong></p>
        <p>Amount: <strong>₦{{ number_format($pledge->amount, 2) }}</strong></p>
        <p>Status: <strong>{{ ucfirst($pledge->status) }}</strong></p>
        <p>Pledge Due Date: <strong>{{ \Carbon\Carbon::parse($pledge->pledge_date)->format('F j, Y') }}</strong></p>
        <p>Created At: <strong>{{ \Carbon\Carbon::parse($pledge->created_at)->format('F j, Y, g:i A') }}</strong></p>

        @if ($pledge->note)
            <p>Note: <strong>{{ $pledge->note }}</strong></p>
        @endif

        <p style="margin-top:20px;">
            <a href="{{ url('admin/pledges/' . $pledge->id) }}"
                style="background:#2c3e50;color:#fff;padding:10px 18px;text-decoration:none;border-radius:4px;">
                View Pledge
            </a>
        </p>

    </div>
</body>

</html>
