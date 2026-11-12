@extends('layouts.site')
@section('title', 'Youth Registration')
@section('content')
<section class="section auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-side d-none d-lg-flex" style="background-image:url('{{ asset('assets/img/10.jpeg') }}')">
                <div><h2>Skilled Youth</h2><p>Showcase your skills, upload your CV and apply for available opportunities.</p></div>
            </div>
            <div class="auth-form">
                <h1 class="h3">Youth Registration</h1>
                <form method="POST" action="{{ route('register.youth.store') }}">@csrf
                    <div class="mb-3"><label class="form-label" for="name">Full Name</label>
                        <input class="form-control" id="name" name="name" value="{{ old('name') }}" required></div>
                    <div class="mb-3"><label class="form-label" for="email">Email Address</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label" for="phone">Phone Number</label>
                            <input class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label" for="location">Location</label>
                            <input class="form-control" id="location" name="location" value="{{ old('location') }}" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label" for="password">Password</label>
                            <input class="form-control" id="password" type="password" name="password" minlength="8" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" minlength="8" required></div>
                    </div>
                    <button class="btn btn-teal btn-lg w-100">Create Youth Account</button>
                </form>
                <p class="mt-3 mb-0 text-center">Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
