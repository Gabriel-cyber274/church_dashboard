@extends('layouts.report')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Edit Submission</h2>
                            <small class="text-muted">{{ $report->title }}</small>
                        </div>
                        <a href="{{ route('reports.submissions.show', [$report, $submission]) }}" class="btn btn-secondary">
                            Back to Submission
                        </a>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('submissions.update', [$report, $submission]) }}">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                You are editing a submission made on {{ $submission->created_at->format('F j, Y') }}.
                                @if ($submission->updated_at != $submission->created_at)
                                    Last updated: {{ $submission->updated_at->format('F j, Y H:i') }}
                                @endif
                            </div>

                            @foreach ($report->questions as $question)
                                @php
                                    // Convert options to array
                                    if (is_array($question->options)) {
                                        $options = $question->options;
                                    } elseif (is_string($question->options)) {
                                        $options = explode(',', $question->options);
                                    } else {
                                        $options = [];
                                    }
                                    $options = array_map('trim', $options);

                                    // Get existing answer
                                    $existingAnswer = $existingAnswers[$question->id] ?? null;
                                    if ($question->type === 'checkbox' && $existingAnswer) {
                                        $existingAnswer = json_decode($existingAnswer, true) ?: [];
                                    }
                                @endphp

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        {{ $loop->iteration }}. {{ $question->question }}
                                        @if ($question->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    @if ($question->type === 'text')
                                        <textarea name="answers[{{ $question->id }}]"
                                            class="form-control @error('answers.' . $question->id) is-invalid @enderror" rows="3"
                                            {{ $question->is_required ? 'required' : '' }}>{{ old('answers.' . $question->id, $existingAnswer) }}</textarea>
                                    @elseif($question->type === 'radio' && count($options))
                                        <div>
                                            @foreach ($options as $option)
                                                <div class="form-check">
                                                    <input type="radio" name="answers[{{ $question->id }}]"
                                                        value="{{ $option }}" class="form-check-input"
                                                        {{ old('answers.' . $question->id, $existingAnswer) == $option ? 'checked' : '' }}
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
                                                        {{ in_array($option, old('answers.' . $question->id, $existingAnswer ?? [])) ? 'checked' : '' }}>
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
                                                    {{ old('answers.' . $question->id, $existingAnswer) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="answers[{{ $question->id }}]"
                                            class="form-control @error('answers.' . $question->id) is-invalid @enderror"
                                            value="{{ old('answers.' . $question->id, $existingAnswer) }}"
                                            {{ $question->is_required ? 'required' : '' }}>
                                    @endif

                                    @error('answers.' . $question->id)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Submission
                                    </button>
                                    <a href="{{ route('reports.submissions.show', [$report, $submission]) }}"
                                        class="btn btn-secondary">Cancel</a>
                                </div>

                                @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="fas fa-trash"></i> Delete Submission
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('submissions.destroy', [$report, $submission]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this submission? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
