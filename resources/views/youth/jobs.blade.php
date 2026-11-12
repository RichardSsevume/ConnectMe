@extends('layouts.dashboard')
@section('title', 'Browse Jobs')
@section('content')
<div class="page-head">
    <h1>Find Your Next Opportunity</h1>
    <p>Explore available vacancies from employers looking for people with your skills and qualifications.</p>
</div>
@include('partials.job-filters', ['action' => route('youth.jobs')])
<div class="mt-4">@include('partials.job-list', ['variant' => 'dashboard'])</div>
@endsection
