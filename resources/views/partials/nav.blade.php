<header class="cm-nav">
    <nav class="navbar navbar-expand-lg container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Connect Me logo" width="42" height="42"><span>CONNECT ME</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Find Jobs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('find-youth') ? 'active' : '' }}" href="{{ route('find-youth') }}">Find Skilled Youth</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                @include('partials.theme-toggle')
                @auth
                    <a class="btn btn-teal btn-sm" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge-high"></i> My Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-outline-navy btn-sm">Logout</button>
                    </form>
                @else
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-teal btn-sm" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>
</header>
