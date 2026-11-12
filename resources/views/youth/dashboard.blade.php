@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
<div class="welcome">
    <h1>Welcome Back, {{ $user->firstName() }}!</h1>
    <p>Your next career opportunity could be just a click away. Explore available vacancies and let employers discover your skills.</p>
    <a class="btn btn-light cta-btn" href="{{ route('youth.jobs') }}"><i class="fa-solid fa-magnifying-glass"></i> Browse Jobs</a>
</div>

@unless($user->visible_to_employers)
    <div class="alert alert-info"><i class="fa-solid fa-eye-slash"></i> Your profile is currently hidden from employers. Tick <strong>Allow employers to view my profile</strong> in <a href="{{ route('youth.profile') }}">My Profile</a> so employers can discover your skills.</div>
@endunless

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-hourglass-half"></i><div><div class="n">{{ $counts['pending'] ?? 0 }}</div><div class="l">Pending applications</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-circle-check"></i><div><div class="n">{{ $counts['approved'] ?? 0 }}</div><div class="l">Approved</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-circle-xmark"></i><div><div class="n">{{ $counts['rejected'] ?? 0 }}</div><div class="l">Rejected</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-folder-open"></i><div><div class="n">{{ $documents }}/5</div><div class="l">Documents uploaded</div></div></div></div>
</div>

<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Recent Applications</h2><a href="{{ route('youth.applications') }}">View all</a></div>
    @if($recent->isEmpty())
        <p class="text-muted mb-0">You have not applied for any vacancy yet. <a href="{{ route('youth.jobs') }}">Browse jobs</a> to get started.</p>
    @else
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Job Title</th><th>Company</th><th>Date Applied</th><th>Status</th></tr></thead>
            <tbody>@foreach($recent as $a)
                <tr><td>{{ $a->vacancy->title }}</td><td>{{ $a->vacancy->employer->displayName() }}</td><td>{{ $a->created_at->format('d/m/Y') }}</td>
                    <td><span class="badge text-bg-{{ $a->statusClass() }}">{{ ucfirst($a->status) }}</span></td></tr>
            @endforeach</tbody>
        </table></div>
    @endif
</div>
@endsection
