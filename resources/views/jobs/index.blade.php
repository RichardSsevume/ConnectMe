@extends('layouts.site')
@section('title', 'Find Jobs')
@section('content')
<section class="page-banner">
    <div class="container">
        <h1>Find Your Next Opportunity</h1>
        <p>Explore available vacancies from employers looking for people with your skills and qualifications.</p>
    </div>
</section>
<section class="section pt-4">
    <div class="container">
        @include('partials.job-filters', ['action' => route('jobs.index')])
        <div class="mt-4">@include('partials.job-list', ['variant' => 'public'])</div>
    </div>
</section>
@endsection
