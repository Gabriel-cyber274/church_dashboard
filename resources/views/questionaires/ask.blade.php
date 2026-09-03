@extends('layouts.questionaire')

@section('title', $programme ? 'Ask a question' : 'Enter questionnaire code')

@section('content')
    <div class="container container-narrow">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @unless ($programme)
                    <div class="card">
                        <div class="card-body p-4">
                            <h1 class="h4 mb-2">Ask a question</h1>
                            <p class="text-muted mb-4">Enter the questionnaire code you were given to continue.</p>

                            <form method="POST" action="{{ route('questionaires.lookup') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="code" class="form-label">Questionnaire code</label>
                                    <input type="text" name="code" id="code"
                                        class="form-control text-uppercase @error('code') is-invalid @enderror"
                                        value="{{ old('code') }}" required maxlength="20" autocomplete="off"
                                        placeholder="e.g. AB12CD">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h1 class="h4 mb-1">{{ $programme->name }}</h1>
                                    @if ($programme->description)
                                        <p class="text-muted mb-0">{{ $programme->description }}</p>
                                    @endif
                                </div>
                                <span class="code-pill">{{ $programme->code }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">What would you like to ask?</h2>
                            <form method="POST" action="{{ route('questionaires.questions.store', $programme->code) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="question" class="form-label">Your question</label>
                                    <textarea name="question" id="question" rows="5"
                                        class="form-control @error('question') is-invalid @enderror" required maxlength="2000"
                                        placeholder="Type your question here...">{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">Submit question</button>
                                    <a href="{{ route('questionaires.ask') }}" class="btn btn-outline-secondary">Use a
                                        different code</a>
                                </div>
                            </form>
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection
