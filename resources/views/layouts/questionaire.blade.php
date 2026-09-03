<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Questionnaires')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-color: #f8f9fa;
            --border-color: #eaeaea;
            --text-muted: #7f8c8d;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0.85rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            height: 48px;
            margin-right: 12px;
        }

        .nav-link {
            font-weight: 500;
        }

        .container-narrow {
            max-width: 860px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .code-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #e8f4fc;
            color: var(--accent-color);
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.08em;
            font-size: 0.9rem;
        }

        .programme-card {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .programme-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        .chat-wrap {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            min-height: 420px;
            display: flex;
            flex-direction: column;
        }

        .chat-list {
            flex: 1;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .chat-bubble {
            max-width: 85%;
            background: #eef6fc;
            border-radius: 16px 16px 16px 4px;
            padding: 0.85rem 1rem;
        }

        .chat-bubble .meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .chat-bubble p {
            margin: 0;
            white-space: pre-wrap;
        }

        .empty-chat {
            margin: auto;
            text-align: center;
            color: var(--text-muted);
            padding: 3rem 1rem;
        }

        .form-control,
        .form-control:focus {
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            @if (request()->routeIs('questionaires.ask', 'questionaires.ask.show', 'questionaires.lookup', 'questionaires.questions.store'))
                <span class="navbar-brand mb-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Church Logo" class="navbar-logo">
                    Questionnaires
                </span>
            @else
                <a class="navbar-brand" href="{{ route('questionaires.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Church Logo" class="navbar-logo">
                    Questionnaires
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#questionaireNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="questionaireNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('questionaires.index') }}">All questionnaires</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('questionaires.ask') }}">Ask a question</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
