<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Pledge Recorded</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px;margin:auto;background:#fff;border:1px solid #ddd;">

        {{-- Header --}}
        <div style="background:#2c3e50;padding:30px;text-align:center;color:#fff;">
            @if (file_exists($logoPath))
                <img src="{{ $message->embed($logoPath) }}" style="max-width:140px;margin-bottom:15px;">
            @endif
            <h2 style="margin:0;">New Pledge Recorded</h2>
        </div>

        {{-- Content --}}
        <div style="padding:30px;">

            <h3 style="margin-top:0;color:#2c3e50;">
                {{ $itemName }}
            </h3>

            <p style="font-size:32px;font-weight:bold;color:#27ae60;text-align:center;">
                ₦{{ number_format($pledge->amount, 2) }}
            </p>

            <hr>

            <h4>Pledge Details</h4>
            <p><strong>Pledge Date:</strong> {{ $pledge->pledge_date->format('F j, Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pledge->status) }}</p>

            @if ($pledge->note)
                <p><strong>Note:</strong> {{ $pledge->note }}</p>
            @endif

            <hr>

            <h4>Donor Information</h4>
            <p><strong>Name:</strong> {{ $member->first_name }} {{ $member->last_name }}</p>
            <p><strong>Phone:</strong> {{ $member->phone_number }}</p>
            <p><strong>Email:</strong> {{ $member->email }}</p>

            <hr>

            <p style="text-align:center;">
                <a href="{{ url('admin/pledges/' . $pledge->id) }}"
                    style="background:#2c3e50;color:#fff;padding:10px 18px;text-decoration:none;border-radius:4px;">
                    View Pledge
                </a>
            </p>

        </div>

        {{-- Footer --}}
        <div style="background:#fafafa;padding:20px;text-align:center;font-size:13px;color:#666;">
            <p>This is an automated notification from {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>

    </div>
</body>

</html>
