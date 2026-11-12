@extends('layouts.site')
@section('title', 'Connecting Skills to Opportunities')
@section('content')

{{-- Main banner --}}
<section class="hero" style="background-image:url('{{ asset('assets/img/16.jpg') }}')">
    <div class="hero-overlay"></div>
    <div class="container hero-inner text-center">
        <p class="hero-tag">Your Skills. Your Opportunity. Your Future.</p>
        <h1>Connecting Skills to Opportunities.</h1>
        <p class="hero-text">Connect Me is a platform that brings skilled young people and employers together. Whether you are looking for employment or searching for talented workers, we make it easier to find the right opportunity or the right person.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a class="btn btn-teal btn-lg" href="{{ route('jobs.index') }}">Find a Job</a>
            <a class="btn btn-light-outline btn-lg" href="{{ route('find-youth') }}">Find Skilled Workers</a>
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="section">
    <div class="container">
        <div class="section-head"><h2>How It Works</h2></div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="how-card h-100">
                    <img src="{{ asset('assets/img/18.jpg') }}" alt="Young graduates ready to start their careers">
                    <div class="p-4">
                        <h3><i class="fa-solid fa-user-graduate"></i> For Skilled Youth</h3>
                        <p>Looking for a job? Create your profile, showcase your skills, upload your CV and explore available job opportunities. Apply for vacancies and track your applications from one place.</p>
                        <a class="btn btn-teal" href="{{ route('register.youth') }}">Join as a Job Seeker</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="how-card h-100">
                    <img src="{{ asset('assets/img/9.jpeg') }}" alt="Professionals working together at their desks">
                    <div class="p-4">
                        <h3><i class="fa-solid fa-building"></i> For Employers</h3>
                        <p>Looking for talented workers? Register your organisation, advertise job vacancies, search for skilled individuals and manage applications with ease.</p>
                        <a class="btn btn-navy" href="{{ route('register.employer') }}">Join as an Employer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why choose --}}
<section class="section section-tint">
    <div class="container">
        <div class="section-head"><h2>Why Choose Connect Me?</h2></div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3"><div class="why-card"><div class="why-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <h3>Easy Job Search</h3><p>Find employment opportunities that match your skills, qualifications and interests.</p></div></div>
            <div class="col-md-6 col-lg-3"><div class="why-card"><div class="why-icon"><i class="fa-solid fa-handshake"></i></div>
                <h3>Direct Employer Connection</h3><p>Connect with employers looking for people with your skills.</p></div></div>
            <div class="col-md-6 col-lg-3"><div class="why-card"><div class="why-icon"><i class="fa-solid fa-list-check"></i></div>
                <h3>Application Tracking</h3><p>Monitor your applications and know whether they are pending, approved or rejected.</p></div></div>
            <div class="col-md-6 col-lg-3"><div class="why-card"><div class="why-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>Verified Accounts</h3><p>Connect with verified employers and skilled individuals for a more trustworthy experience.</p></div></div>
        </div>
    </div>
</section>

{{-- Latest opportunities --}}
<section class="section">
    <div class="container">
        <div class="section-head"><h2>Explore Latest Opportunities</h2>
            <p>Discover available vacancies from employers looking for talented and skilled individuals.</p></div>
        @if($latest->count())
            <div class="row g-4">
                @foreach($latest as $vacancy)
                    <div class="col-md-6 col-lg-4">@include('partials.job-card', ['vacancy' => $vacancy])</div>
                @endforeach
            </div>
        @else
            @include('partials.empty-jobs', ['variant' => 'public'])
        @endif
        <div class="text-center mt-4"><a class="btn btn-navy btn-lg" href="{{ route('jobs.index') }}">View All Jobs</a></div>
    </div>
</section>

{{-- Call to action --}}
<section class="cta">
    <div class="container text-center">
        <h2>Your Next Opportunity Starts Here!</h2>
        <p>Don't let your skills go unnoticed. Join Connect Me today and take the next step towards your career.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a class="btn btn-light btn-lg cta-btn" href="{{ route('register.youth') }}">Register as Youth</a>
            <a class="btn btn-light-outline btn-lg" href="{{ route('register.employer') }}">Register as Employer</a>
        </div>
    </div>
</section>
@endsection
