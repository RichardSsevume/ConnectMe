@extends('layouts.site')
@section('title', 'About Us')
@section('content')
<section class="page-banner"><div class="container"><h1>About Connect Me</h1></div></section>

<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="lead-text">Connect Me is an online employment platform designed to bridge the gap between skilled young people and employers in need of workers.</p>
                <p>The platform provides a central place where young people can showcase their skills, upload their CVs, search for employment opportunities and apply for available vacancies.</p>
                <p>It also enables employers to advertise job opportunities, search for skilled individuals and manage applications efficiently.</p>
            </div>
            <div class="col-lg-6"><img class="rounded-img" src="{{ asset('assets/img/17.jpg') }}" alt="A team collaborating around a laptop"></div>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6"><div class="mv-card"><div class="why-icon"><i class="fa-solid fa-bullseye"></i></div>
                <h2>Our Mission</h2><p>To connect skilled young people with employers by providing an accessible, reliable and efficient employment platform.</p></div></div>
            <div class="col-md-6"><div class="mv-card"><div class="why-icon"><i class="fa-solid fa-eye"></i></div>
                <h2>Our Vision</h2><p>To become a trusted platform that creates employment opportunities and promotes skills development among young people.</p></div></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="section-head text-start mb-3"><h2>Our Objectives</h2></div>
                <ol class="objectives">
                    <li>To provide a platform where skilled young people can showcase their abilities.</li>
                    <li>To help employers easily find and recruit suitable workers.</li>
                    <li>To simplify the process of advertising and applying for job vacancies.</li>
                    <li>To improve communication between job seekers and employers.</li>
                    <li>To promote transparency through account verification and application tracking.</li>
                </ol>
            </div>
            <div class="col-lg-6 order-lg-1"><img class="rounded-img" src="{{ asset('assets/img/7.jpeg') }}" alt="Young people collaborating in a computer lab"></div>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="container">
        <div class="section-head"><h2>Who Can Use Connect Me?</h2></div>
        <div class="row g-4">
            <div class="col-md-6"><div class="mv-card"><div class="why-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <h3>Job Seekers</h3><p>Young people with different skills, qualifications and professional abilities looking for employment opportunities.</p></div></div>
            <div class="col-md-6"><div class="mv-card"><div class="why-icon"><i class="fa-solid fa-building"></i></div>
                <h3>Employers</h3><p>Businesses, organisations, companies and individuals looking for skilled workers.</p></div></div>
        </div>
    </div>
</section>
@endsection
