@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-5 text-center">Contribution Opportunities</h1>

        @if ($programs->isNotEmpty())
            <section>
                <h2 class="section-title">Programs</h2>
                <p class="text-muted mb-4">Church programs and initiatives</p>

                <div class="row">
                    @foreach ($programs as $program)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card project-card">
                                @if ($program->flier_url)
                                    <img src="{{ $program->flier_url }}" class="card-img-top" alt="{{ $program->name }} flier"
                                        style="max-height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="mb-2">{{ $program->name }}</h5>
                                    <p class="text-muted mb-3">{{ Str::limit($program->description, 120) }}</p>

                                    @if ($program->is_budgeted)
                                        <div class="mb-3">
                                            <div class="budget-badge">
                                                ₦{{ number_format($program->budget, 2) }}
                                            </div>
                                        </div>
                                    @endif

                                    <a href="{{ route('contributions.program.show', $program) }}"
                                        class="btn btn-primary mt-auto">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($projects->isNotEmpty())
            <section class="mb-5">
                <h2 class="section-title">Projects</h2>
                <p class="text-muted mb-4">Ongoing projects that need your support</p>

                <div class="row">
                    @foreach ($projects as $project)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card project-card">
                                @if ($project->flier_url)
                                    <img src="{{ $project->flier_url }}" class="card-img-top"
                                        alt="{{ $project->name }} flier" style="max-height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="mb-2">{{ $project->name }}</h5>
                                    <p class="text-muted mb-3">{{ Str::limit($project->description, 120) }}</p>

                                    <div class="mb-3">
                                        <div class="budget-badge">
                                            ₦{{ number_format($project->budget, 2) }}
                                        </div>
                                    </div>

                                    <a href="{{ route('contributions.project.show', $project) }}"
                                        class="btn btn-primary mt-auto">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif



        @if ($projects->isEmpty() && $programs->isEmpty())
            <div class="text-center py-5">
                <div class="card border-0 bg-light">
                    <div class="card-body py-5">
                        <h3 class="text-muted">No contribution opportunities available at the moment</h3>
                        <p class="text-muted mt-2">Please check back later</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
