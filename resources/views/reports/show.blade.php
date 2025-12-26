@extends('layouts.report')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $report->title }}</h2>
                        <div>
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to Reports</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <h4>Description</h4>
                            <p>{{ $report->description }}</p>
                        </div>

                        @if ($report->departments->isNotEmpty())
                            <div class="mb-4">
                                <h4>Departments</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($report->departments as $department)
                                        <span class="badge bg-primary">{{ $department->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($report->members->isNotEmpty())
                            <div class="mb-4">
                                <h4>Assigned Members</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($report->members as $member)
                                        <span class="badge bg-success">{{ $member->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h4>Questions ({{ $report->questions->count() }})</h4>
                            @if ($report->questions->isEmpty())
                                <p class="text-muted">No questions added to this report.</p>
                            @else
                                <div class="list-group">
                                    @foreach ($report->questions as $question)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        {{ $loop->iteration }}. {{ $question->question }}
                                                        @if ($question->is_required)
                                                            <span class="badge bg-danger">Required</span>
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">
                                                        Type: {{ ucfirst($question->type) }}
                                                        @if ($question->options && is_array($question->options) && count($question->options) > 0)
                                                            | Options: {{ implode(', ', $question->options) }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('submissions.create', $report) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Submit Answers
                            </a>
                            <a href="{{ route('reports.submissions', $report) }}" class="btn btn-info">
                                <i class="fas fa-list"></i> View Submissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
