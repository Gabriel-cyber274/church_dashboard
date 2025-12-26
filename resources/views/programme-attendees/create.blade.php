<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Attendee - {{ $program->name ?? 'Program' }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Register Attendee for: {{ $program->name }}</h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('programme-attendees.store', $program->id) }}">
                            @csrf

                            <!-- Name (required if no member_id) -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>

                            <!-- Phone Number (required if no member_id) -->
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" required>
                            </div>

                            <!-- Attendance Time -->
                            <div class="mb-3">
                                <label for="attendance_time" class="form-label">Attendance Time *</label>
                                <input type="datetime-local" class="form-control" id="attendance_time"
                                    name="attendance_time"
                                    value="{{ old('attendance_time', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Register Attendee</button>
                                <a href="{{ route('programme-attendees.index', $program->id) }}"
                                    class="btn btn-secondary">View All Attendees</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
