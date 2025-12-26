@extends('layouts.report')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Submissions for: {{ $report->title }}</h1>
            <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary">Back to Report</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            <th>Department / Member</th>
                            <th>Dept. Leader?</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->id }}</td>
                                <td>{{ $submission->user->name ?? 'N/A' }}</td>

                                {{-- Show member if exists, else department --}}
                                <td>
                                    @if ($submission->member_id)
                                        {{ $submission->member->full_name ?? 'N/A' }}
                                    @else
                                        {{ $submission->user->department->name ?? 'N/A' }}
                                    @endif
                                </td>

                                {{-- Dept leader only relevant if no member --}}
                                <td>
                                    @if (!$submission->member_id && $submission->user)
                                        {{ $submission->user->is_department_leader ? 'Yes' : 'No' }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>{{ $submission->created_at->format('Y-m-d H:i') }}</td>

                                <td>
                                    <a href="{{ route('reports.submissions.show', [$report, $submission]) }}"
                                        class="btn btn-sm btn-info">
                                        View Answers
                                    </a>

                                    @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
                                        <a href="{{ route('submissions.edit', [$report, $submission]) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('submissions.destroy', [$report, $submission]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $submissions->links() }}
            </div>
        </div>
    </div>
@endsection
