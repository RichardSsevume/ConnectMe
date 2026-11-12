@extends('layouts.dashboard')
@section('title', 'Find Skilled Youth')
@section('content')
<div class="page-head">
    <h1>Discover Skilled Individuals</h1>
    <p>Search for talented young people based on their skills, qualifications and location.</p>
</div>
@php
    $locations = \App\Models\User::where('role', 'youth')->where('visible_to_employers', true)
        ->whereNotNull('location')->orderBy('location')->pluck('location')->unique()->values();
    $quals = array_merge(\App\Models\User::EDUCATION_LEVELS, ['First Class Degree', 'Second Class Upper', 'Second Class Lower']);
    $quals = array_values(array_unique($quals));
@endphp
<form class="filter-bar" method="GET" action="{{ route('employer.find-youth') }}">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label" for="name">1. Name (skilled workers)</label>
            <input class="form-control" id="name" name="name" value="{{ request('name') }}" placeholder="e.g. Aisha"></div>
        <div class="col-md-4"><label class="form-label" for="qualification">2. Qualifications (class of degree, former university, etc.)</label>
            <input class="form-control" id="qualification" name="qualification" list="qualList" value="{{ request('qualification') }}" placeholder="e.g. Bachelor's Degree, Makerere University" autocomplete="off">
            <datalist id="qualList">
                @foreach($quals as $q)<option value="{{ $q }}">@endforeach
                @foreach(['Makerere University','Kyambogo University','Mbarara University','Uganda Christian University','Ndejje University','Kampala International University','Uganda Martyrs University','Gulu University'] as $u)<option value="{{ $u }}">@endforeach
            </datalist></div>
        <div class="col-md-3"><label class="form-label" for="location">3. Location (area of residence)</label>
            <input class="form-control" id="location" name="location" list="locList" value="{{ request('location') }}" placeholder="e.g. Kampala" autocomplete="off">
            <datalist id="locList">
                @foreach(collect(['Kampala','Wakiso','Entebbe','Jinja','Mukono','Mbarara','Gulu','Masaka','Mbale','Fort Portal'])->merge($locations)->unique() as $l)<option value="{{ $l }}">@endforeach
            </datalist></div>
        <div class="col-md-2 d-grid"><button class="btn btn-teal"><i class="fa-solid fa-magnifying-glass"></i> Search</button></div>
    </div>
</form>

<div class="mt-4">
@if($youth->isEmpty())
    <div class="empty-state text-center">
        <img class="empty-img" src="{{ asset('assets/img/no-vacancies.svg') }}" alt="">
        <h3>No profiles match your search</h3>
        <p>Try different keywords or check again later. New skilled young people join regularly.</p>
    </div>
@else
    <div class="row g-4">
    @foreach($youth as $p)
        <div class="col-md-6 col-xl-4">
            <article class="job-card h-100">
                <div class="d-flex gap-3 align-items-center mb-3">
                    @include('partials.avatar', ['u' => $p, 'size' => 64])
                    <div><h3 class="job-title">{{ $p->name }}</h3>
                        <div class="job-company">{{ $p->professional_title ?: 'Skilled youth' }}</div>
                        @include('partials.verified', ['u' => $p])</div>
                </div>
                <ul class="job-meta">
                    <li><i class="fa-solid fa-location-dot"></i> {{ $p->location ?: 'Not specified' }}</li>
                    <li><i class="fa-solid fa-graduation-cap"></i> <span><strong>Education:</strong> {{ $p->education_level ?: 'Not specified' }}</span></li>
                </ul>
                <div class="mb-3">@foreach($p->skillList() as $skill)<span class="chip">{{ $skill }}</span>@endforeach</div>
                <div class="d-flex gap-2 flex-wrap mt-auto">
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('employer.youth.show', $p) }}">View Profile</a>
                    <div class="dropdown">
                        <button class="btn btn-teal btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Contact Skilled Employee</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="mailto:{{ $p->email }}"><i class="fa-solid fa-envelope"></i> Email</a></li>
                            @if($p->phone)<li><a class="dropdown-item" href="tel:{{ $p->phone }}"><i class="fa-solid fa-phone"></i> Phone</a></li>@endif
                            @if($p->whatsappNumber())<li><a class="dropdown-item" target="_blank" rel="noopener" href="https://wa.me/{{ $p->whatsappNumber() }}"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a></li>@endif
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    @endforeach
    </div>
    <div class="mt-4">{{ $youth->links() }}</div>
@endif
</div>
@endsection
