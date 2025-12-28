<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pledge Reminder</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px;margin:auto;background:#fff;border:1px solid #ddd;">

        {{-- Header --}}
        <div style="background:#2c3e50;padding:30px;text-align:center;color:#fff;">
            @if (file_exists($logoPath))
                <img src="{{ $message->embed($logoPath) }}" style="max-width:140px;margin-bottom:15px;">
            @else
                <img src="{{ $logoUrl }}" style="max-width:140px;margin-bottom:15px;">
            @endif

            <h2 style="margin:0;">
                {{ $daysLeft === 0 ? 'Pledge Due Today' : 'Upcoming Pledge Reminder' }}
            </h2>
        </div>

        {{-- Content --}}
        <div style="padding:30px;">

            <p>
                Dear <strong>{{ $member->full_name }}</strong>,
            </p>

            <p>
                This is a friendly reminder about your pledge.
            </p>

            <p style="font-size:28px;font-weight:bold;color:#27ae60;text-align:center;">
                ₦{{ number_format($pledge->amount, 2) }}
            </p>

            <p style="text-align:center;">
                <strong>
                    {{ $daysLeft === 0 ? 'Your pledge is due today.' : "Your pledge is due in {$daysLeft} days." }}
                </strong>
            </p>

            <hr>

            <h4>Pledge Details</h4>
            <p><strong>Pledge Due Date:</strong> {{ \Carbon\Carbon::parse($pledge->pledge_date)->format('F j, Y') }}
            </p>
            <p><strong>Status:</strong> {{ ucfirst($pledge->status) }}</p>
            <p><strong>Created At:</strong> {{ \Carbon\Carbon::parse($pledge->created_at)->format('F j, Y, g:i A') }}
            </p>

            @if ($pledge->note)
                <p><strong>Note:</strong> {{ $pledge->note }}</p>
            @endif

            <hr>

            {{-- <p style="text-align:center;">
                <a href="{{ url('admin/pledges/' . $pledge->id) }}"
                    style="background:#2c3e50;color:#fff;padding:10px 18px;text-decoration:none;border-radius:4px;">
                    View Pledge
                </a>
            </p> --}}
        </div>

        {{-- Footer --}}
        <div style="background:#fafafa;padding:20px;text-align:center;font-size:13px;color:#666;">
            <p>This is an automated reminder from {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>

    </div>

</body>

</html>
