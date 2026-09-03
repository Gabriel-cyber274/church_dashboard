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

        @if ($errors->updateProgramme->any())
            <div class="alert alert-danger">
                {{ $errors->updateProgramme->first() }}
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
                    @php
                        $isInvalidProgramme =
                            $errors->updateProgramme->any() && (int) old('programme_id') === $programme->id;
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card programme-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h5 class="mb-0">
                                        <a href="{{ route('questionaires.show', $programme) }}"
                                            class="text-decoration-none text-reset stretched-link">{{ $programme->name }}</a>
                                    </h5>
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
                            <div class="card-footer bg-white border-top-0 pt-0 pb-3 card-actions">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#editProgrammeModal{{ $programme->id }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteProgrammeModal{{ $programme->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editProgrammeModal{{ $programme->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('questionaires.update', $programme) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="programme_id" value="{{ $programme->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit questionnaire</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted small mb-3">Code <strong>{{ $programme->code }}</strong>
                                            stays the same.</p>
                                        <div class="mb-3">
                                            <label for="edit-name-{{ $programme->id }}"
                                                class="form-label">Name</label>
                                            <input type="text"
                                                class="form-control @if ($isInvalidProgramme && $errors->updateProgramme->has('name')) is-invalid @endif"
                                                id="edit-name-{{ $programme->id }}" name="name"
                                                value="{{ $isInvalidProgramme ? old('name') : $programme->name }}"
                                                required maxlength="255">
                                            @if ($isInvalidProgramme && $errors->updateProgramme->has('name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->updateProgramme->first('name') }}</div>
                                            @endif
                                        </div>
                                        <div class="mb-0">
                                            <label for="edit-description-{{ $programme->id }}"
                                                class="form-label">Description <span
                                                    class="text-muted">(optional)</span></label>
                                            <textarea class="form-control @if ($isInvalidProgramme && $errors->updateProgramme->has('description')) is-invalid @endif"
                                                id="edit-description-{{ $programme->id }}" name="description" rows="3" maxlength="255">{{ $isInvalidProgramme ? old('description') : $programme->description }}</textarea>
                                            @if ($isInvalidProgramme && $errors->updateProgramme->has('description'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->updateProgramme->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteProgrammeModal{{ $programme->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('questionaires.destroy', $programme) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete questionnaire</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-2">Delete <strong>{{ $programme->name }}</strong>
                                            ({{ $programme->code }})?</p>
                                        <p class="text-muted mb-0">
                                            {{ $programme->questions_count }}
                                            {{ Str::plural('question', $programme->questions_count) }}
                                            will be deleted as well. This cannot be undone.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

    @if ($errors->updateProgramme->any() && old('programme_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('editProgrammeModal{{ old('programme_id') }}');
                if (modal) {
                    new bootstrap.Modal(modal).show();
                }
            });
        </script>
    @endif
@endsection
