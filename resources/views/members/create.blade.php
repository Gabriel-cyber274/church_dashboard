<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Member</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f4f6f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: #212529;
        }

        .card-body {
            padding: 1.75rem;
        }

        label {
            font-weight: 500;
            color: #495057;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        /* Select2 Styling */
        .select2-container--default .select2-selection--multiple {
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 6px;
        }

        .select2-selection__choice {
            background-color: #0d6efd;
            border: none;
            color: #fff;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 0.85rem;
        }

        .select2-selection__choice__remove {
            color: #fff;
            margin-right: 6px;
        }

        .form-footer {
            border-top: 1px solid #e9ecef;
            padding-top: 1.25rem;
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .optional {
            font-weight: normal;
            color: #6c757d;
        }

        .optional::after {
            content: " (optional)";
            color: #6c757d;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Create New Member</h4>
                        <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#qrModal">
                            QR Code
                        </button>
                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('members.store') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label class="required">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ old('first_name') }}" required>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label class="required">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ old('last_name') }}" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-12">
                                    <label class="optional">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email') }}">
                                    <small class="text-muted">Optional</small>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <label class="required">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth"
                                        value="{{ old('date_of_birth') }}" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <label class="optional">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number"
                                        value="{{ old('phone_number') }}">
                                    <small class="text-muted">Optional</small>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12">
                                    <label class="required">Address</label>
                                    <textarea class="form-control" rows="2" name="address" required>{{ old('address') }}</textarea>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="required">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                                    </select>
                                </div>

                                <!-- Marital Status -->
                                <div class="col-md-6">
                                    <label class="required">Marital Status</label>
                                    <select class="form-select" name="marital_status" required>
                                        <option value="">Select Status</option>
                                        <option value="single" @selected(old('marital_status') === 'single')>Single</option>
                                        <option value="married" @selected(old('marital_status') === 'married')>Married</option>
                                        <option value="divorced" @selected(old('marital_status') === 'divorced')>Divorced</option>
                                        <option value="widowed" @selected(old('marital_status') === 'widowed')>Widowed</option>
                                    </select>
                                </div>

                                <!-- Departments -->
                                <div class="col-md-12">
                                    <label class="optional">Departments</label>
                                    <select class="form-select select2" name="departments[]" multiple>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @selected(is_array(old('departments')) && in_array($department->id, old('departments')))>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Optional - you may select more than one department</small>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="reset" class="btn btn-light">Clear</button>
                                <button type="submit" class="btn btn-primary px-4">Create Member</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Page QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                    <button class="btn btn-success" onclick="downloadQRCode()">Download QR Code</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <script>
        $(function() {
            $('.select2').select2({
                placeholder: "Select departments (optional)",
                width: '100%'
            });
        });

        let qrGenerated = false;
        const modal = document.getElementById('qrModal');

        modal.addEventListener('shown.bs.modal', function() {
            if (!qrGenerated) {
                new QRCode(document.getElementById("qrcode"), {
                    text: window.location.href,
                    width: 220,
                    height: 220,
                });
                qrGenerated = true;
            }
        });

        function downloadQRCode() {
            const qrCanvas = document.querySelector('#qrcode canvas');
            if (!qrCanvas) return;

            const qrImage = qrCanvas.toDataURL("image/png");
            const link = document.createElement('a');
            link.href = qrImage;
            link.download = 'page-qrcode.png';
            link.click();
        }
    </script>
</body>

</html>
