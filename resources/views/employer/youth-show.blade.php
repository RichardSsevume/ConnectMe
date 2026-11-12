@extends('layouts.dashboard')
@section('title', 'Candidate Profile')
@section('content')
<a href="{{ route('employer.find-youth') }}" class="small"><i class="fa-solid fa-arrow-left"></i> Back to Find Skilled Youth</a>
<div class="panel mt-2">
    <div class="d-flex gap-4 align-items-center flex-wrap mb-4">
        @include('partials.avatar', ['u' => $user, 'size' => 110])
        <div>
            <h1 class="h3 mb-1">{{ $user->name }} @include('partials.verified', ['u' => $user])</h1>
            <div class="text-muted">{{ $user->professional_title ?: 'Skilled youth' }}</div>
            <div class="text-muted"><i class="fa-solid fa-location-dot"></i> {{ $user->location ?: 'Not specified' }} &middot; <i class="fa-solid fa-graduation-cap"></i> {{ $user->education_level ?: 'Not specified' }}</div>
        </div>
    </div>
    <div class="mb-3">@foreach($user->skillList() as $skill)<span class="chip">{{ $skill }}</span>@endforeach</div>
    @if($user->bio)<h2 class="h6">About</h2><p>{!! nl2br(e($user->bio)) !!}</p>@endif
    @if($user->work_experience)<h2 class="h6">Work Experience</h2><p>{!! nl2br(e($user->work_experience)) !!}</p>@endif
    @if($user->portfolio_url)<p><strong>Portfolio:</strong> <a href="{{ $user->portfolio_url }}" target="_blank" rel="noopener">{{ $user->portfolio_url }}</a></p>@endif

    @if($documents->isNotEmpty())
        <h2 class="h6 mt-4">Documents (shared with you because this candidate applied to your vacancy)</h2>
        @foreach($documents as $doc)<div><a href="{{ $doc->url() }}" target="_blank" rel="noopener"><i class="fa-solid fa-paperclip"></i> {{ \App\Models\Document::TYPES[$doc->type][0] ?? $doc->type }}</a></div>@endforeach
    @endif

    <hr>
    <h2 class="h6">Contact Candidate</h2>
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-outline-navy" href="mailto:{{ $user->email }}"><i class="fa-solid fa-envelope"></i> Email</a>
        @if($user->phone)<a class="btn btn-outline-navy" href="tel:{{ $user->phone }}"><i class="fa-solid fa-phone"></i> Phone</a>@endif
        @if($user->whatsappNumber())<a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/{{ $user->whatsappNumber() }}"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>@endif
    </div>
    <p class="form-text mt-2 mb-0">Contact details are shown to logged-in employers only.</p>
</div>
@endsection
