@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Confirm Your Contribution</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <p class="text-muted mb-4">
                            Please fill in your details to confirm your contribution.
                        </p>

                        <form method="POST" action="{{ route('contributions.confirm') }}">
                            @csrf

                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="id" value="{{ $id }}">



                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required
                                    placeholder="Enter your email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}" required
                                    placeholder="Enter your phone number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Contribution Amount (₦) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                        id="amount" name="amount" value="{{ old('amount') }}" required min="1"
                                        step="0.01" placeholder="0.00">
                                </div>
                                <small class="form-text text-muted">
                                    Enter the amount you contributed or pledged
                                </small>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_pledge') is-invalid @enderror" type="checkbox"
                                        name="is_pledge" id="is_pledge" value="1"
                                        {{ old('is_pledge') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_pledge">
                                        This is a pledge fulfilment (I'm sending money for a previous pledge)
                                    </label>
                                </div>
                                @error('is_pledge')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <span style="margin-right: 8px;">✓</span> Confirm
                                    {{ old('is_pledge') ? 'Pledge' : 'Contribution' }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ url()->previous() }}" class="text-decoration-none">
                                    ← Go Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isPledgeCheckbox = document.getElementById('is_pledge');
            const submitButton = document.querySelector('button[type="submit"]');
            const amountLabel = document.querySelector('label[for="amount"]');

            // Function to update button text based on pledge status
            function updateButtonText() {
                if (isPledgeCheckbox.checked) {
                    submitButton.innerHTML = '<span style="margin-right: 8px;">✓</span> Confirm Pledge';
                    amountLabel.textContent = 'Pledged Amount (₦) *';
                } else {
                    submitButton.innerHTML = '<span style="margin-right: 8px;">✓</span> Confirm Contribution';
                    amountLabel.textContent = 'Contribution Amount (₦) *';
                }
            }

            // Initial update
            updateButtonText();

            // Update on checkbox change
            isPledgeCheckbox.addEventListener('change', updateButtonText);

            // Amount input formatting
            const amountInput = document.getElementById('amount');

            amountInput.addEventListener('input', function(e) {
                let value = e.target.value;

                // Remove any non-numeric characters except decimal point
                value = value.replace(/[^\d.]/g, '');

                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }

                // Limit to 2 decimal places
                if (parts.length === 2 && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }

                e.target.value = value;
            });

            // Format on blur
            amountInput.addEventListener('blur', function(e) {
                if (e.target.value) {
                    e.target.value = parseFloat(e.target.value).toFixed(2);
                }
            });
        });
    </script>
@endsection
@endsection
