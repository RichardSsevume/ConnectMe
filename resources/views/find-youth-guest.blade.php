@extends('layouts.site')
@section('title', 'Find Skilled Youth')
@section('content')
<section class="page-banner">
    <div class="container">
        <h1>Discover Skilled Individuals</h1>
        <p>Search for talented young people based on their skills, qualifications and location.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6"><img class="rounded-img" src="{{ asset('assets/img/8.jpeg') }}" alt="Skilled young people working on laptops"></div>
            <div class="col-lg-6">
                <h2>Looking for talented workers?</h2>
                <p>Connect Me currently has <strong>{{ $count }}</strong> skilled {{ \Illuminate\Support\Str::plural('profile', $count) }} open to employers. To protect our young people's privacy, profiles and contact details are visible only to logged-in employers.</p>
                <p>Register your organisation, advertise vacancies and find talented individuals.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-teal btn-lg" href="{{ route('register.employer') }}">Register as Employer</a>
                    <a class="btn btn-outline-navy btn-lg" href="{{ route('login') }}">Login</a>
                </div>
                @auth
                    @unless(auth()->user()->isEmployer())
                        <p class="text-muted mt-3 mb-0">You are logged in with a {{ auth()->user()->role }} account. Only employer accounts can search for skilled youth.</p>
                    @endunless
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
