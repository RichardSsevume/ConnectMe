@extends('layouts.site')
@section('title', $vacancy->title)
@section('content')
@php $emp = $vacancy->employer; @endphp
<section class="page-banner">
    <div class="container">
        <h1>{{ $vacancy->title }}</h1>
        <p>{{ $emp->displayName() }} &middot; {{ $vacancy->location }} &middot; {{ $vacancy->employment_type }}</p>
    </div>
</section>
<section class="section pt-4">
    <div class="container">
        @unless($vacancy->isListed())
            <div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation"></i> This vacancy is not visible to the public. Status: <strong>{{ $vacancy->statusLabel() }}</strong>.</div>
        @endunless
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        @include('partials.avatar', ['u' => $emp, 'size' => 56])
                        <div><strong>{{ $emp->displayName() }}</strong> @include('partials.verified', ['u' => $emp])
                            <div class="text-muted small">{{ $emp->industry }}</div></div>
                    </div>
                    <h2 class="h5">Job Description</h2>
                    <p>{!! nl2br(e($vacancy->description)) !!}</p>
                    <h2 class="h5 mt-4">Required Skills</h2>
                    <p>@foreach(array_filter(array_map('trim', explode(',', $vacancy->required_skills))) as $skill)<span class="chip">{{ $skill }}</span>@endforeach</p>
                    @if($vacancy->qualifications)<h2 class="h5 mt-4">Academic Qualifications</h2><p>{{ $vacancy->qualifications }}</p>@endif
                    @if($vacancy->experience)<h2 class="h5 mt-4">Experience Required</h2><p>{{ $vacancy->experience }}</p>@endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-card mb-4">
                    <ul class="job-meta mb-0">
                        <li><i class="fa-solid fa-location-dot"></i> <span><strong>Location:</strong> {{ $vacancy->location }}</span></li>
                        <li><i class="fa-solid fa-briefcase"></i> <span><strong>Employment type:</strong> {{ $vacancy->employment_type }}</span></li>
                        <li><i class="fa-solid fa-tag"></i> <span><strong>Industry:</strong> {{ $vacancy->category }}</span></li>
                        <li><i class="fa-solid fa-users"></i> <span><strong>Available positions:</strong> {{ $vacancy->slotsLeft() }} of {{ $vacancy->slots }}</span></li>
                        <li><i class="fa-regular fa-calendar"></i> <span><strong>Application deadline:</strong> {{ $vacancy->deadline->format('d M Y') }}</span></li>
                        @if($vacancy->contact_info)<li><i class="fa-solid fa-address-book"></i> <span><strong>Contact:</strong> {{ $vacancy->contact_info }}</span></li>@endif
                    </ul>
                </div>
                <div class="form-card" id="apply">
                    @guest
                        <p class="mb-3">Log in or register as a job seeker to apply for this vacancy.</p>
                        <a class="btn btn-teal w-100 mb-2" href="{{ route('login') }}">Login to Apply</a>
                        <a class="btn btn-outline-navy w-100" href="{{ route('register.youth') }}">Register as Youth</a>
                    @else
                        @if(auth()->user()->isYouth())
                            @if($myApplication && $myApplication->status !== 'withdrawn')
                                <p class="mb-2">You applied for this vacancy.</p>
                                <span class="badge text-bg-{{ $myApplication->statusClass() }} fs-6">{{ ucfirst($myApplication->status) }}</span>
                            @elseif($vacancy->isListed() && $vacancy->slotsLeft() > 0)
                                <form method="POST" action="{{ route('youth.apply', $vacancy) }}">@csrf
                                    <label class="form-label" for="cover_note">Short note to the employer (optional)</label>
                                    <textarea class="form-control mb-3" id="cover_note" name="cover_note" rows="4" maxlength="1000"></textarea>
                                    <button class="btn btn-teal w-100">Apply Now</button>
                                </form>
                            @else
                                <div class="alert alert-warning mb-0">This vacancy is no longer accepting applications.</div>
                            @endif
                        @else
                            <p class="mb-0 text-muted">Only skilled youth accounts can apply for vacancies.</p>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
