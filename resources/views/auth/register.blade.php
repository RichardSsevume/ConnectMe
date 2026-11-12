@extends('layouts.site')
@section('title', 'Register')
@section('content')
<section class="page-banner">
    <div class="container">
        <h1>Join Connect Me Today!</h1>
        <p>Create an account and become part of a platform that connects skills to opportunities.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="how-card h-100">
                    <img src="{{ asset('assets/img/6.jpeg') }}" alt="A young woman working on her laptop">
                    <div class="p-4">
                        <h3><i class="fa-solid fa-user-graduate"></i> Skilled Youth</h3>
                        <p>Looking for employment? Register to showcase your skills, upload your CV and apply for available opportunities.</p>
                        <a class="btn btn-teal" href="{{ route('register.youth') }}">Register as Youth</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="how-card h-100">
                    <img src="{{ asset('assets/img/9.jpeg') }}" alt="Colleagues working together at their desks">
                    <div class="p-4">
                        <h3><i class="fa-solid fa-building"></i> Employer</h3>
                        <p>Looking for skilled workers? Register your organisation, advertise vacancies and find talented individuals.</p>
                        <a class="btn btn-navy" href="{{ route('register.employer') }}">Register as Employer</a>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center mt-4">Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
    </div>
</section>
@endsection
