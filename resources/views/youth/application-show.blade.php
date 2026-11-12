@extends('layouts.dashboard')
@section('title', 'View Application')
@section('content')
@php $v = $application->vacancy; @endphp
<a href="{{ route('youth.applications') }}" class="small"><i class="fa-solid fa-arrow-left"></i> Back to My Applications</a>
<div class="page-head mt-2"><h1>{{ $v->title }}</h1><p>{{ $v->employer->displayName() }} &middot; {{ $v->location }}</p></div>
<div class="row g-4">
    <div class="col-lg-7"><div class="panel h-100">
        <h2 class="h5">Your Application</h2>
        <p class="mb-1"><strong>Status:</strong> <span class="badge text-bg-{{ $application->statusClass() }}">{{ ucfirst($application->status) }}</span></p>
        <p class="mb-1"><strong>Date applied:</strong> {{ $application->created_at->format('d/m/Y') }}</p>
        <p class="mt-3 mb-1"><strong>Your note to the employer</strong></p>
        <p>{{ $application->cover_note ?: 'You did not add a note.' }}</p>
        @if($application->status === 'pending')
            <form method="POST" action="{{ route('youth.withdraw', $application) }}" onsubmit="return confirm('Withdraw this application?')">@csrf @method('PATCH')
                <button class="btn btn-outline-danger">Withdraw Application</button>
            </form>
        @endif
    </div></div>
    <div class="col-lg-5"><div class="panel h-100">
        <h2 class="h5">Vacancy Summary</h2>
        <ul class="job-meta mb-3">
            <li><i class="fa-solid fa-briefcase"></i> <span><strong>Type:</strong> {{ $v->employment_type }}</span></li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i> <span><strong>Required skills:</strong> {{ $v->required_skills }}</span></li>
            <li><i class="fa-regular fa-calendar"></i> <span><strong>Deadline:</strong> {{ $v->deadline->format('d M Y') }}</span></li>
            @if($v->contact_info)<li><i class="fa-solid fa-address-book"></i> <span><strong>Contact:</strong> {{ $v->contact_info }}</span></li>@endif
        </ul>
        @if($v->isListed())<a class="btn btn-outline-navy btn-sm" href="{{ route('jobs.show', $v) }}">View Details</a>@endif
    </div></div>
</div>
@endsection
