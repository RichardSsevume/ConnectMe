@extends('layouts.dashboard')
@section('title', 'My Progress Report')
@section('content')
@php
    $rate = $totals['success_rate'];
    if ($totals['total'] === 0) { $verdict = ['secondary', 'No activity yet', 'You have not applied for any vacancy in this report window. Browse jobs and apply to start measuring your progress.']; }
    elseif ($totals['approved'] > 0) { $verdict = ['success', 'The system is helping you', "You have {$totals['approved']} approved application(s) so far. Keep your profile and documents up to date to keep this momentum."]; }
    elseif ($totals['pending'] > 0) { $verdict = ['warning', 'Progress in motion', "You have {$totals['pending']} application(s) waiting for employer decisions. Complete your profile and documents to improve your chances."]; }
    else { $verdict = ['danger', 'Needs improvement', 'Your applications have not been successful yet. Update your skills, upload your CV and certificates, and apply to vacancies that match your qualifications.']; }
@endphp
<div class="page-head d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div><h1>My Progress Report</h1><p>See how Connect Me is helping you: weekly, monthly and annual statistics.</p></div>
    <div class="d-flex gap-2 no-print">@include('partials.report-periods')
        <button class="btn btn-sm btn-navy" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Report</button></div>
</div>

@include('partials.report-header', ['title' => 'Skilled Youth Progress Report - ' . \App\Services\ReportService::periodTitle($period), 'for' => $user->name])

<div class="panel mb-4 report-id">
    <div class="row g-3">
        <div class="col-md-4"><div class="small text-muted">Name</div><strong>{{ $user->name }}</strong></div>
        <div class="col-md-4"><div class="small text-muted">Professional title</div><strong>{{ $user->professional_title ?: 'Not specified' }}</strong></div>
        <div class="col-md-4"><div class="small text-muted">Report period</div><strong>{{ \App\Services\ReportService::periodTitle($period) }}</strong></div>
    </div>
</div>

<div class="alert alert-{{ $verdict[0] }}"><strong>{{ $verdict[1] }}.</strong> {{ $verdict[2] }}</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-paper-plane"></i><div><div class="n">{{ $totals['total'] }}</div><div class="l">Applications sent</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-circle-check"></i><div><div class="n">{{ $totals['approved'] }}</div><div class="l">Approved</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-percent"></i><div><div class="n">{{ $rate === null ? '-' : $rate . '%' }}</div><div class="l">Success rate (of decided)</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-building"></i><div><div class="n">{{ $employersContacted }}</div><div class="l">Employers reached</div></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7"><div class="panel h-100"><h2 class="h5">Applications over time</h2>@include('partials.report-bars')</div></div>
    <div class="col-lg-5"><div class="panel h-100">
        <h2 class="h5">Profile strength</h2>
        <div class="progress mb-2" style="height:14px"><div class="progress-bar" style="width:{{ $profilePct }}%"></div></div>
        <p class="mb-2">Profile completeness: <strong>{{ $profilePct }}%</strong></p>
        <p class="mb-2">Documents uploaded: <strong>{{ $docs }}/5</strong></p>
        <p class="mb-0">Visible to employers: <strong>{{ $user->visible_to_employers ? 'Yes' : 'No' }}</strong> &middot; Verification: <strong>{{ ucfirst($user->verification_status) }}</strong></p>
    </div></div>
</div>

<div class="panel mt-4"><h2 class="h5">{{ ucfirst($period) }} statistics</h2>@include('partials.report-table')</div>

<div class="row g-4 mt-1">
    <div class="col-lg-5"><div class="panel h-100"><h2 class="h5">Applications by industry</h2>
        @forelse($byIndustry as $name => $d)<div class="status-row"><span>{{ $name }}</span><span><strong>{{ $d['total'] }}</strong> <span class="text-muted small">({{ $d['approved'] }} approved)</span></span></div>
        @empty<p class="text-muted mb-0">No applications yet.</p>@endforelse
    </div></div>
    <div class="col-lg-7"><div class="panel h-100"><h2 class="h5">Recent applications</h2>
        @forelse($recent as $a)<div class="status-row"><div><strong>{{ $a->vacancy->title }}</strong><div class="small text-muted">{{ $a->vacancy->employer->displayName() }} &middot; {{ $a->created_at->format('d/m/Y') }}</div></div><span class="badge text-bg-{{ $a->statusClass() }}">{{ ucfirst($a->status) }}</span></div>
        @empty<p class="text-muted mb-0">No applications yet.</p>@endforelse
    </div></div>
</div>
<p class="small text-muted mt-3 print-only">This report was generated automatically by Connect Me from your account activity.</p>
@endsection
