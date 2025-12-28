@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <h2>{{ $program->name }}</h2>
                    <p class="lead text-muted">{{ $program->description }}</p>

                    @if ($program->flier_url)
                        <div class="my-4">
                            <h5>Program Flier</h5>
                            <img src="{{ $program->flier_url }}" class="img-fluid rounded" alt="{{ $program->name }} flier"
                                style="max-height: 400px;">
                        </div>
                    @endif

                    <div class="budget-badge mb-4">
                        <h5>Budget: ₦{{ number_format($program->budget, 2) }}</h5>
                    </div>

                    <!-- Primary Contribution Options -->
                    <div class="row mb-5">
                        <!-- Paystack Option -->
                        <div class="col-md-4 mb-3">
                            <div class="card contribution-card h-100">
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="mb-3">
                                        <i class="fas fa-credit-card fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Instant Online Payment</h5>
                                    <p class="card-text text-muted flex-grow-1">Pay securely with Paystack (Card, Bank
                                        Transfer, USSD)</p>
                                    <button type="button" class="btn btn-primary btn-lg w-100 mt-auto"
                                        data-bs-toggle="modal" data-bs-target="#paystackModal">
                                        <i class="fas fa-credit-card me-2"></i> Pay Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pledge Option -->
                        <div class="col-md-4 mb-3">
                            <div class="card contribution-card h-100">
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="mb-3">
                                        <i class="fas fa-handshake fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title">Make a Pledge</h5>
                                    <p class="card-text text-muted flex-grow-1">Commit to contribute later and fulfill when
                                        convenient</p>
                                    <button type="button" class="btn btn-warning btn-lg w-100 mt-auto"
                                        data-bs-toggle="modal" data-bs-target="#pledgeModal">
                                        <i class="fas fa-calendar-check me-2"></i> Make a Pledge
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Church Accounts Option -->
                        <div class="col-md-4 mb-3">
                            <div class="card contribution-card h-100">
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="mb-3">
                                        <i class="fas fa-university fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title">Bank Transfer</h5>
                                    <p class="card-text text-muted flex-grow-1">Transfer directly to church accounts</p>
                                    <button type="button" class="btn btn-success btn-lg w-100 mt-auto"
                                        data-bs-toggle="modal" data-bs-target="#bankAccountsModal">
                                        <i class="fas fa-university me-2"></i> View Accounts
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paystack Modal -->
    <div class="modal fade" id="paystackModal" tabindex="-1" aria-labelledby="paystackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('contributions.paystack.initiate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="program">
                    <input type="hidden" name="id" value="{{ $program->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="paystackModalLabel">
                            <i class="fas fa-credit-card me-2 text-primary"></i> Instant Online Payment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Your email address"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control" placeholder="Your phone number"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₦)</label>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount"
                                min="100" required>
                            <small class="form-text text-muted">Minimum amount: ₦100</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-shield-alt me-2"></i>
                            Payments are processed securely through Paystack
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-lock me-2"></i> Pay Securely
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pledge Modal -->
    <div class="modal fade" id="pledgeModal" tabindex="-1" aria-labelledby="pledgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="pledgeForm" action="{{ route('pledges.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="program">
                    <input type="hidden" name="id" value="{{ $program->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="pledgeModalLabel">
                            <i class="fas fa-handshake me-2 text-warning"></i> Make a Pledge
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Your full name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Your email address"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control"
                                placeholder="Your phone number" required>
                        </div>

                        <div class="mb-3">
                            <label for="pledgeAmount" class="form-label">Pledge Amount (₦)</label>
                            <input type="number" class="form-control" id="pledgeAmount" name="amount"
                                placeholder="Enter pledge amount" min="100" required>
                            <small class="form-text text-muted">Minimum pledge: ₦100</small>
                        </div>

                        <div class="mb-3">
                            <label for="pledgeDate" class="form-label">When do you plan to fulfill this pledge?</label>
                            <input type="date" class="form-control" id="pledgeDate" name="fulfillment_date"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="pledgeNote" class="form-label">Optional Note</label>
                            <textarea class="form-control" id="pledgeNote" name="note" rows="3"
                                placeholder="Add any notes about your pledge..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You'll receive a reminder before your chosen fulfillment date.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-handshake me-2"></i> Submit Pledge
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bank Accounts Modal -->
    <div class="modal fade" id="bankAccountsModal" tabindex="-1" aria-labelledby="bankAccountsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bankAccountsModalLabel">
                        <i class="fas fa-university me-2 text-success"></i> Church Bank Accounts for Transfer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Transfer to any of the accounts below, then click "I Have Made My Transfer" to confirm your payment.
                    </p>

                    @forelse ($program->banks as $bank)
                        <div class="card mb-3 bank-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Bank Name:</strong> {{ $bank->bank_name }}</p>
                                        <p class="mb-2"><strong>Account Number:</strong> <span
                                                class="text-primary fw-bold">{{ $bank->account_number }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Account Holder:</strong>
                                            {{ $bank->account_holder_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            No bank accounts have been attached to this program yet.
                        </div>
                    @endforelse
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('contributions.confirm.form', ['type' => 'program', 'id' => $program->id]) }}"
                        class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> I Have Made My Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .budget-badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                display: inline-block;
                margin: 10px 0;
            }

            /* Clean, uniform contribution cards */
            .contribution-card {
                border: 2px solid #e9ecef;
                border-radius: 12px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .contribution-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                border-color: #dee2e6;
            }

            .contribution-card .card-body {
                padding: 2rem;
            }

            .contribution-card .card-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: #2c3e50;
            }

            .contribution-card .card-text {
                font-size: 0.95rem;
                line-height: 1.5;
                min-height: 60px;
            }

            .contribution-card i.fa-3x {
                margin-bottom: 1rem;
            }

            /* Bank card styling in modal */
            .bank-card {
                border-left: 4px solid #198754;
                background-color: #f8f9fa;
                transition: all 0.2s ease;
            }

            .bank-card:hover {
                background-color: #e9ecef;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            /* Form styling */
            .form-control {
                border-radius: 8px;
                padding: 12px 15px;
                font-size: 1rem;
                border: 1px solid #ced4da;
            }

            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
            }

            /* Button styling */
            .btn-lg {
                padding: 12px 24px;
                font-size: 1.1rem;
                border-radius: 8px;
                font-weight: 500;
            }

            /* Modal styling */
            .modal-content {
                border-radius: 12px;
                border: none;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }

            .modal-header {
                border-bottom: 2px solid #f1f1f1;
                padding: 1.5rem;
            }

            .modal-footer {
                border-top: 2px solid #f1f1f1;
                padding: 1.5rem;
            }

            /* Alert styling */
            .alert {
                border-radius: 8px;
                border: none;
            }

            .alert-info {
                background-color: #e7f3ff;
                color: #004085;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Validate amount inputs
                const amountInputs = document.querySelectorAll('input[name="amount"]');

                amountInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        if (this.value < 100) {
                            this.setCustomValidity('Minimum amount is ₦100');
                        } else {
                            this.setCustomValidity('');
                        }
                    });
                });

                // Set minimum date for pledge
                const today = new Date().toISOString().split('T')[0];
                const pledgeDate = document.getElementById('pledgeDate');
                if (pledgeDate) pledgeDate.min = today;
            });
        </script>
    @endpush
@endsection
