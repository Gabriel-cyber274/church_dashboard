<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees - {{ $program->name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h2>Attendees for: {{ $program->name }}</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#qrModal">
                    QR Code
                </button>
                <a href="{{ route('programme-attendees.create', $program->id) }}" class="btn btn-primary">
                    Register New Attendee
                </a>
            </div>
        </div>

        @if ($attendees->isEmpty())
            <div class="alert alert-info">
                No attendees registered for this program yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Attendance Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendees as $index => $attendee)
                            <tr>
                                <td>{{ $attendees->count() - $index }}</td>
                                <td>{{ $attendee->member_id ? $attendee->member->full_name : $attendee->name }}</td>
                                <td>{{ $attendee->member_id ? $attendee->member->phone_number : $attendee->phone_number }}
                                </td>
                                <td>{{ $attendee->member_id ? $attendee->member->email : $attendee->email }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($attendee->attendance_time)->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendee Registration QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <div id="qrcode" class="d-flex justify-content-center mb-3"></div>

                    <button class="btn btn-success" onclick="downloadQRCode()">
                        Download QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        const attendeeUrl = "{{ route('programme-attendees.create', $program->id) }}";
        let qrGenerated = false;

        const modal = document.getElementById('qrModal');

        modal.addEventListener('shown.bs.modal', function() {
            if (!qrGenerated) {
                new QRCode(document.getElementById("qrcode"), {
                    text: attendeeUrl,
                    width: 220,
                    height: 220,
                });
                qrGenerated = true;
            }
        });

        function downloadQRCode() {
            const qrCanvas = document.querySelector('#qrcode canvas');
            const qrImage = qrCanvas.toDataURL("image/png");

            const link = document.createElement('a');
            link.href = qrImage;
            link.download = 'attendee-registration-qrcode.png';
            link.click();
        }
    </script>

</body>

</html>
