@php
    $me = auth()->user();
    $menus = [
        'youth' => [
            ['youth.dashboard', 'fa-gauge-high', 'Dashboard', ['youth.dashboard']],
            ['youth.profile', 'fa-user', 'My Profile', ['youth.profile*']],
            ['youth.documents', 'fa-cloud-arrow-up', 'Upload Documents', ['youth.documents*']],
            ['youth.jobs', 'fa-magnifying-glass', 'Browse Jobs', ['youth.jobs']],
            ['youth.applications', 'fa-file-lines', 'My Applications', ['youth.applications*']],
            ['youth.status', 'fa-list-check', 'Application Status', ['youth.status']],
            ['youth.report', 'fa-chart-line', 'My Progress Report', ['youth.report']],
        ],
        'employer' => [
            ['employer.dashboard', 'fa-gauge-high', 'Dashboard', ['employer.dashboard']],
            ['employer.company', 'fa-building', 'Company Profile', ['employer.company*']],
            ['employer.vacancies.create', 'fa-bullhorn', 'Post Vacancy', ['employer.vacancies.create']],
            ['employer.vacancies', 'fa-briefcase', 'Manage Vacancies', ['employer.vacancies', 'employer.vacancies.edit', 'employer.applicants']],
            ['employer.find-youth', 'fa-user-group', 'Find Skilled Youth', ['employer.find-youth', 'employer.youth.*']],
            ['employer.statistics', 'fa-chart-column', 'Application Statistics', ['employer.statistics']],
            ['employer.report', 'fa-file-lines', 'Recruitment Report', ['employer.report']],
        ],
        'admin' => [
            ['admin.dashboard', 'fa-gauge-high', 'Dashboard', ['admin.dashboard']],
            ['admin.verify', 'fa-user-check', 'Verify Accounts', ['admin.verify']],
            ['admin.users', 'fa-users', 'Manage Users', ['admin.users', 'admin.users.*']],
            ['admin.vacancies', 'fa-briefcase', 'Manage Vacancies', ['admin.vacancies']],
            ['admin.messages', 'fa-envelope', 'Contact Messages', ['admin.messages']],
            ['admin.report', 'fa-chart-pie', 'Platform Report', ['admin.report']],
        ],
    ];
    $roleLabel = ['youth' => 'Skilled Youth', 'employer' => 'Employer', 'admin' => 'Administrator'][$me->role];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Connect Me</title>
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">
    @include('partials.head')
</head>
<body class="dash-body">
<div class="dash">
    <aside class="side offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar" aria-label="Dashboard menu">
        <div class="side-top">
            <a class="side-brand" href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="" width="38" height="38"><span>CONNECT ME</span></a>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
        </div>
        <div class="side-user">
            @include('partials.avatar', ['u' => $me, 'size' => 52])
            <div class="min-w-0">
                <div class="side-name">{{ $me->displayName() }}</div>
                <div class="side-role">{{ $roleLabel }}</div>
                @include('partials.verified', ['u' => $me])
            </div>
        </div>
        <nav class="side-nav">
            @foreach($menus[$me->role] as [$route, $icon, $label, $patterns])
                <a class="side-link {{ request()->routeIs(...$patterns) ? 'active' : '' }}" href="{{ route($route) }}">
                    <i class="fa-solid {{ $icon }}"></i><span>{{ $label }}</span>
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="side-link w-100 border-0 bg-transparent text-start"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></button>
            </form>
        </nav>
    </aside>

    <div class="dash-main">
        <div class="dash-top">
            <button class="btn btn-outline-navy btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
            <span class="dash-top-title">@yield('title', 'Dashboard')</span>
            <span class="ms-auto d-flex gap-2 align-items-center">@include('partials.theme-toggle')<a class="btn btn-sm btn-outline-navy" href="{{ route('home') }}"><i class="fa-solid fa-globe"></i> View Website</a></span>
        </div>
        <div class="dash-content">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.theme-script')
</body>
</html>
