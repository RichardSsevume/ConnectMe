@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
<div class="welcome">
    <h1>Welcome Back, {{ $user->displayName() }}!</h1>
    <p>Find the right people for your organisation. Advertise vacancies, discover skilled individuals and manage your recruitment activities from one place.</p>
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-light cta-btn" href="{{ route('employer.vacancies.create') }}"><i class="fa-solid fa-bullhorn"></i> Post Vacancy</a>
        <a class="btn btn-light-outline" href="{{ route('employer.find-youth') }}"><i class="fa-solid fa-user-group"></i> Find Skilled Youth</a>
    </div>
</div>

@if($user->verification_status !== 'verified')
    <div class="alert alert-info"><i class="fa-solid fa-shield-halved"></i> Your account is awaiting verification by the Connect Me administrator. Verified employers earn more trust from job seekers.</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-briefcase"></i><div><div class="n">{{ $stats['vacancies'] }}</div><div class="l">Vacancies</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-door-open"></i><div><div class="n">{{ $stats['open'] }}</div><div class="l">Open vacancies</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-users"></i><div><div class="n">{{ $stats['applicants'] }}</div><div class="l">Total applicants</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-bullseye"></i><div><div class="n">{{ $stats['slots_left'] }}</div><div class="l">Remaining slots</div></div></div></div>
</div>

<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Pending Applications</h2><a href="{{ route('employer.statistics') }}">Application statistics</a></div>
    @if($recent->isEmpty())
        <p class="text-muted mb-0">No pending applications right now.</p>
    @else
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Applicant</th><th>Vacancy</th><th>Applied</th><th class="text-end">Action</th></tr></thead>
            <tbody>@foreach($recent as $a)
                <tr><td>{{ $a->applicant->name }}</td><td>{{ $a->vacancy->title }}</td><td>{{ $a->created_at->format('d/m/Y') }}</td>
                    <td class="text-end"><a class="btn btn-outline-navy btn-sm" href="{{ route('employer.applicants', $a->vacancy) }}">Review</a></td></tr>
            @endforeach</tbody>
        </table></div>
    @endif
</div>
@endsection
