@extends('layouts.questionaire')

@section('title', 'Questionnaires')

@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Questionnaires</h1>
                <p class="text-muted mb-0">Create a programme and share its code so people can ask questions.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgrammeModal">
                Create
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($programmes->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <h5 class="mb-2">No questionnaires yet</h5>
                    <p class="text-muted mb-3">Create one to start collecting questions.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createProgrammeModal">
                        Create questionnaire
                    </button>
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($programmes as $programme)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="{{ route('questionaires.show', $programme) }}" class="card programme-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">{{ $programme->name }}</h5>
                                    <span class="code-pill">{{ $programme->code }}</span>
                                </div>
                                @if ($programme->description)
                                    <p class="text-muted mb-3">{{ $programme->description }}</p>
                                @else
                                    <p class="text-muted mb-3">No description</p>
                                @endif
                                <small class="text-muted">
                                    {{ $programme->questions_count }}
                                    {{ Str::plural('question', $programme->questions_count) }}
                                </small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="modal fade" id="createProgrammeModal" tabindex="-1" aria-labelledby="createProgrammeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('questionaires.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProgrammeModalLabel">Create questionnaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">A unique code will be generated automatically after you save.</p>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label for="description" class="form-label">Description <span
                                    class="text-muted">(optional)</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3" maxlength="255">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('createProgrammeModal')).show();
            });
        </script>
    @endif
@endsection
