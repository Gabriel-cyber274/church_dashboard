<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Church Contributions')</title>
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
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .container {
            max-width: 1200px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #219653;
            transform: translateY(-1px);
        }

        h2,
        h4,
        h5 {
            font-weight: 600;
            color: var(--primary-color);
        }

        h2 {
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .bank-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid var(--accent-color);
        }

        .bank-card p {
            margin-bottom: 0.5rem;
        }

        .bank-card strong {
            color: var(--secondary-color);
            min-width: 160px;
            display: inline-block;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .mt-4 {
            margin-top: 2.5rem !important;
        }

        .mb-4 {
            margin-bottom: 2.5rem !important;
        }

        .mb-5 {
            margin-bottom: 4rem !important;
        }

        .budget-badge {
            background-color: #e8f4fc;
            color: var(--accent-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .section-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .project-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .project-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .project-card .btn {
            margin-top: auto;
            align-self: flex-start;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .card-body {
                padding: 1.25rem;
            }

            .bank-card strong {
                min-width: 140px;
            }
        }


        .project-card img {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .project-card .card-body {
            padding: 1.25rem;
        }

        /* Make the entire card clickable */
        .project-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="{{ route('contributions.index') }}">
                Church Contributions
            </a>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</body>

</html>
