@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <h2>{{ $program->name }}</h2>
                    <p class="lead text-muted">{{ $program->description }}</p>
                    <div class="budget-badge mt-2">
                        Budget: ₦{{ number_format($program->budget, 2) }}
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <h4 class="mb-3">Bank Accounts for Contributions</h4>

                        @forelse ($program->banks as $bank)
                            <div class="card mb-3 bank-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Bank Name:</strong> {{ $bank->bank_name }}</p>
                                            <p><strong>Account Number:</strong> {{ $bank->account_number }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Account Holder:</strong> {{ $bank->account_holder_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border" role="alert">
                                No bank accounts have been attached to this program yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Confirmation</h5>
                        <p class="text-muted mb-4">
                            After making your contribution to one of the bank accounts listed above, please confirm your
                            payment by clicking the button below.
                        </p>

                        <form method="POST" action="{{ route('contributions.confirm') }}">
                            @csrf
                            <button class="btn btn-success px-4">
                                <span style="margin-right: 8px;">✓</span> I Have Sent My Contribution
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
