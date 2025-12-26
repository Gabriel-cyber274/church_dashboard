@extends('layouts.report')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $report->title }}</h2>
                        <p class="mb-0">{{ $report->description }}</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('submissions.store', $report) }}">
                            @csrf

                            @foreach ($report->questions as $question)
                                @php
                                    // Convert comma-separated options string to array
                                    if (is_array($question->options)) {
                                        $options = $question->options;
                                    } elseif (is_string($question->options)) {
                                        $options = explode(',', $question->options);
                                    } else {
                                        $options = [];
                                    }
                                    $options = array_map('trim', $options); // remove extra spaces
                                @endphp

                                <div class="mb-4">
                                    <label class="form-label">
                                        {{ $question->question }}
                                        @if ($question->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    @if ($question->type === 'text')
                                        <textarea name="answers[{{ $question->id }}]"
                                            class="form-control @error('answers.' . $question->id) is-invalid @enderror" rows="3"
                                            {{ $question->is_required ? 'required' : '' }}>{{ old('answers.' . $question->id) }}</textarea>
                                    @elseif($question->type === 'radio' && count($options))
                                        <div>
                                            @foreach ($options as $option)
                                                <div class="form-check">
                                                    <input type="radio" name="answers[{{ $question->id }}]"
                                                        value="{{ $option }}" class="form-check-input"
                                                        {{ old('answers.' . $question->id) == $option ? 'checked' : '' }}
                                                        {{ $question->is_required ? 'required' : '' }}>
                                                    <label class="form-check-label">{{ $option }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'checkbox' && count($options))
                                        <div>
                                            @foreach ($options as $option)
                                                <div class="form-check">
                                                    <input type="checkbox" name="answers[{{ $question->id }}][]"
                                                        value="{{ $option }}" class="form-check-input"
                                                        {{ in_array($option, old('answers.' . $question->id, [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $option }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'select' && count($options))
                                        <select name="answers[{{ $question->id }}]"
                                            class="form-select @error('answers.' . $question->id) is-invalid @enderror"
                                            {{ $question->is_required ? 'required' : '' }}>
                                            <option value="">Select an option</option>
                                            @foreach ($options as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('answers.' . $question->id) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="answers[{{ $question->id }}]"
                                            class="form-control @error('answers.' . $question->id) is-invalid @enderror"
                                            value="{{ old('answers.' . $question->id) }}"
                                            {{ $question->is_required ? 'required' : '' }}>
                                    @endif

                                    @error('answers.' . $question->id)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="form-group row mb-0">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        Submit Answers
                                    </button>
                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
