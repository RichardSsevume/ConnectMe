@extends('layouts.site')
@section('title', 'Employer Registration')
@section('content')
<section class="section auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-side d-none d-lg-flex" style="background-image:url('{{ asset('assets/img/9.jpeg') }}')">
                <div><h2>Employer</h2><p>Register your organisation, advertise vacancies and find talented individuals.</p></div>
            </div>
            <div class="auth-form">
                <h1 class="h3">Employer Registration</h1>
                <form method="POST" action="{{ route('register.employer.store') }}">@csrf
                    <div class="mb-3"><label class="form-label" for="company_name">Company / Organisation Name</label>
                        <input class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required></div>
                    <div class="mb-3"><label class="form-label" for="name">Contact Person</label>
                        <input class="form-control" id="name" name="name" value="{{ old('name') }}" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label" for="email">Email Address</label>
                            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label" for="phone">Phone Number</label>
                            <input class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label" for="location">Company Location</label>
                            <input class="form-control" id="location" name="location" value="{{ old('location') }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label" for="industry">Industry</label>
                            <select class="form-select" id="industry" name="industry" required>
                                <option value="">Select industry</option>
                                @foreach(\App\Models\Vacancy::INDUSTRIES as $industry)
                                    <option value="{{ $industry }}" @selected(old('industry') === $industry)>{{ $industry }}</option>
                                @endforeach
                            </select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label" for="password">Password</label>
                            <input class="form-control" id="password" type="password" name="password" minlength="8" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" minlength="8" required></div>
                    </div>
                    <button class="btn btn-navy btn-lg w-100">Create Employer Account</button>
                </form>
                <p class="mt-3 mb-0 text-center">Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
