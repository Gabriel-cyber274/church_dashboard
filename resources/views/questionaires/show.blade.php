@extends('layouts.questionaire')

@section('title', $programme->name)

@section('content')
    <div class="container container-narrow">
        <div class="mb-3">
            <a href="{{ route('questionaires.index') }}" class="text-decoration-none">&larr; All questionnaires</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $programme->name }}</h1>
                @if ($programme->description)
                    <p class="text-muted mb-0">{{ $programme->description }}</p>
                @endif
            </div>
            <div class="text-end">
                <div class="code-pill mb-1" id="programmeCode">{{ $programme->code }}</div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="copyCodeBtn">Copy code</button>
            </div>
        </div>

        <div class="chat-wrap">
            <div class="chat-list">
                @forelse ($questions as $question)
                    <div class="chat-bubble">
                        <div class="meta">
                            Question #{{ $loop->iteration }}
                            &middot;
                            {{ $question->created_at->format('M j, Y g:i A') }}
                        </div>
                        <p>{{ $question->question }}</p>
                    </div>
                @empty
                    <div class="empty-chat">
                        <h5>No questions yet</h5>
                        <p class="mb-0">Share code <strong>{{ $programme->code }}</strong> so people can ask from the
                            Ask a question page.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('copyCodeBtn')?.addEventListener('click', async function() {
            const code = document.getElementById('programmeCode').innerText.trim();
            try {
                await navigator.clipboard.writeText(code);
                this.textContent = 'Copied';
                setTimeout(() => this.textContent = 'Copy code', 1600);
            } catch (e) {
                this.textContent = 'Copy failed';
            }
        });
    </script>
@endsection
