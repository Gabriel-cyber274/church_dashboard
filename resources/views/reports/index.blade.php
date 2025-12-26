@extends('layouts.report')

@section('content')
    <div class="container">
        <h1>Reports</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @foreach ($reports as $report)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $report->title }}</h5>
                            <p class="card-text">{{ Str::limit($report->description, 100) }}</p>

                            <div class="mb-2">
                                <small class="text-muted">
                                    Questions: {{ $report->questions_count ?? $report->questions->count() }}
                                </small>
                            </div>

                            <a href="{{ route('reports.show', $report) }}" class="btn btn-primary">View Details</a>
                            <a href="{{ route('submissions.create', $report) }}" class="btn btn-success">Submit Answers</a>
                            <a href="{{ route('reports.submissions', $report) }}" class="btn btn-info">View Submissions</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $reports->links() }}
    </div>
@endsection
