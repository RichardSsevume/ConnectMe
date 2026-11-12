@extends('layouts.site')
@section('title', 'Login')
@section('content')
<section class="section auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-side d-none d-lg-flex" style="background-image:url('{{ asset('assets/img/10.jpeg') }}')">
                <div><h2>Your Skills. Your Opportunity. Your Future.</h2></div>
            </div>
            <div class="auth-form">
                <h1 class="h3">Welcome Back!</h1>
                <p class="text-muted">Log in to your Connect Me account to access available opportunities and manage your activities.</p>
                <form method="POST" action="{{ route('login.attempt') }}">@csrf
                    <div class="mb-3"><label class="form-label" for="email">Email Address</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus></div>
                    <div class="mb-3"><label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" type="password" name="password" required></div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label></div>
                    <button class="btn btn-teal btn-lg w-100">Login</button>
                </form>
                <p class="mt-3 mb-0 text-center">Don't have an account? <a href="{{ route('register') }}">Register Here</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
