@extends('layouts.report')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Submission Details</h1>
            <div>
                @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    <a href="{{ route('reports.submissions', $report) }}" class="btn btn-secondary">Back to Submissions</a>
                    <a href="{{ route('submissions.edit', [$report, $submission]) }}" class="btn btn-warning">Edit</a>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Submission Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Report:</strong> {{ $report->title }}</p>
                <p><strong>Submitted By:</strong> {{ $submission->user->name ?? 'N/A' }}</p>
                <p><strong>Department:</strong> {{ $submission->user->department->name ?? 'N/A' }}</p>
                <p><strong>Dept. Leader?:</strong>
                    @if ($submission->user)
                        {{ $submission->user->is_department_leader ? 'Yes' : 'No' }}
                    @else
                        N/A
                    @endif
                </p>
                <p><strong>Submitted On:</strong> {{ $submission->created_at->format('F j, Y H:i') }}</p>
                @if ($submission->updated_at != $submission->created_at)
                    <p><strong>Last Updated:</strong> {{ $submission->updated_at->format('F j, Y H:i') }}</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Answers</h5>
            </div>
            <div class="card-body">
                @foreach ($submission->answers as $answer)
                    <div class="mb-4 pb-3 border-bottom">
                        <h6>{{ $answer->question->question }}</h6>

                        @if ($answer->question->type === 'checkbox')
                            @php
                                $selectedOptions = json_decode($answer->answer, true) ?? [];
                            @endphp
                            @if (is_array($selectedOptions) && count($selectedOptions) > 0)
                                <ul>
                                    @foreach ($selectedOptions as $option)
                                        <li>{{ $option }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No answer provided</p>
                            @endif
                        @else
                            <p>{{ $answer->answer ?? 'No answer provided' }}</p>
                        @endif

                        <small class="text-muted">Question Type: {{ $answer->question->type }}</small>
                    </div>
                @endforeach

                @if ($submission->answers->isEmpty())
                    <p class="text-muted">No answers submitted.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
