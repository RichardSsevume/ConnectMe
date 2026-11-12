@extends('layouts.dashboard')
@section('title', 'Application Status')
@section('content')
@php
    $cats = [
        'pending' => ['Pending', 'fa-hourglass-half', 'Your application has been received and is awaiting review.'],
        'approved' => ['Approved', 'fa-circle-check', 'Your application has been accepted by the employer.'],
        'rejected' => ['Rejected', 'fa-circle-xmark', 'Your application was not successful.'],
        'withdrawn' => ['Withdrawn', 'fa-rotate-left', 'You have withdrawn your application.'],
    ];
@endphp
<div class="page-head">
    <h1>Track Your Applications</h1>
    <p>Stay updated on the progress of your job applications.</p>
</div>
<div class="row g-4">
    @foreach($cats as $key => [$label, $icon, $text])
        @php $items = $applications->get($key, collect()); @endphp
        <div class="col-lg-6">
            <div class="panel h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="status-icon status-{{ $key }}"><i class="fa-solid {{ $icon }}"></i></span>
                    <div><h2 class="h5 mb-0">{{ $label }} <span class="badge text-bg-light border">{{ $items->count() }}</span></h2>
                        <div class="text-muted small">{{ $label }} &ndash; {{ $text }}</div></div>
                </div>
                @forelse($items as $a)
                    <div class="status-row">
                        <div><strong>{{ $a->vacancy->title }}</strong><div class="small text-muted">{{ $a->vacancy->employer->displayName() }} &middot; {{ $a->created_at->format('d/m/Y') }}</div></div>
                        <a class="btn btn-outline-navy btn-sm" href="{{ route('youth.applications.show', $a) }}">View Application</a>
                    </div>
                @empty
                    <p class="text-muted mb-0">No applications in this category.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
