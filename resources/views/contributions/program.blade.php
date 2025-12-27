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
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-credit-card fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Instant Online Payment</h5>
                                    <p class="card-text text-muted">Pay securely with Paystack (Card, Bank Transfer, USSD)
                                    </p>

                                    <!-- Paystack Form -->
                                    <form action="{{ route('contributions.paystack.initiate') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="type" value="program">
                                        <input type="hidden" name="id" value="{{ $program->id }}">

                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Your email address" required>
                                        </div>

                                        <div class="mb-3">
                                            <input type="tel" name="phone_number" class="form-control"
                                                placeholder="Your phone number" required>
                                        </div>

                                        <div class="mb-3">
                                            <input type="number" name="amount" class="form-control"
                                                placeholder="Enter amount (₦)" min="100" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            Pay Securely
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Pledge Option -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-handshake fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title">Make a Pledge</h5>
                                    <p class="card-text text-muted">Commit to contribute later and fulfill when convenient
                                    </p>
                                    <button type="button" class="btn btn-warning btn-lg w-100" data-bs-toggle="modal"
                                        data-bs-target="#pledgeModal">
                                        <i class="fas fa-calendar-check me-2"></i> Make a Pledge
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Church Accounts Option -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-secondary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-university fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="card-title">Bank Transfer</h5>
                                    <p class="card-text text-muted">Transfer directly to church accounts</p>
                                    <button type="button" class="btn btn-outline-secondary btn-lg w-100"
                                        data-bs-toggle="collapse" data-bs-target="#bankAccountsSection">
                                        <i class="fas fa-chevron-down me-2"></i> View Accounts
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Accounts Section (Collapsed by default) -->
                    <div class="collapse" id="bankAccountsSection">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-university me-2"></i>Church Bank Accounts for Transfer
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">
                                    After making your transfer to any of the accounts below, please confirm your payment
                                    using the button at the bottom.
                                </p>

                                @forelse ($program->banks as $bank)
                                    <div class="card mb-3 bank-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Bank Name:</strong> {{ $bank->bank_name }}</p>
                                                    <p><strong>Account Number:</strong> {{ $bank->account_number }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Account Holder:</strong> {{ $bank->account_holder_name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border" role="alert">
                                        No bank accounts have been attached to this program yet.
                                    </div>
                                @endforelse

                                <!-- Confirmation Button for Bank Transfer -->
                                <div class="text-center mt-4">
                                    <a href="{{ route('contributions.confirm.form', ['type' => 'program', 'id' => $program->id]) }}"
                                        class="btn btn-success px-5">
                                        <i class="fas fa-check-circle me-2"></i> I Have Made My Transfer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pledge Modal -->
    <div class="modal fade" id="pledgeModal" tabindex="-1" aria-labelledby="pledgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="pledgeForm" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $program->id }}">
                    <input type="hidden" name="type" value="program">

                    <div class="modal-header">
                        <h5 class="modal-title" id="pledgeModalLabel">Make a Pledge</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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

    <!-- Optional: Add Font Awesome for icons if not already included -->
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

            .bank-card {
                border-left: 4px solid #6c757d;
            }

            .card {
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            #paystackForm .form-control,
            #pledgeForm .form-control {
                border-radius: 8px;
                padding: 12px 15px;
                font-size: 1rem;
            }

            .btn-lg {
                padding: 12px 24px;
                font-size: 1.1rem;
                border-radius: 8px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Validate amount inputs
                const paystackAmount = document.getElementById('paystackAmount');
                const pledgeAmount = document.getElementById('pledgeAmount');

                [paystackAmount, pledgeAmount].forEach(input => {
                    if (!input) return;
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
