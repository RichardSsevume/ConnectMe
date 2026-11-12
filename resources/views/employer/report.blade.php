@extends('layouts.dashboard')
@section('title', 'Recruitment Report')
@section('content')
<div class="page-head d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div><h1>Recruitment Report</h1><p>A summary of your vacancies, applicants and hiring results on Connect Me.</p></div>
    <div class="d-flex gap-2 no-print">@include('partials.report-periods')
        <button class="btn btn-sm btn-navy" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Report</button></div>
</div>

@include('partials.report-header', ['title' => 'Employer Recruitment Report - ' . \App\Services\ReportService::periodTitle($period), 'for' => $user->displayName()])

<div class="panel mb-4 report-id">
    <div class="row g-3">
        <div class="col-md-3"><div class="small text-muted">Company</div><strong>{{ $user->displayName() }}</strong></div>
        <div class="col-md-3"><div class="small text-muted">Industry</div><strong>{{ $user->industry ?: 'Not specified' }}</strong></div>
        <div class="col-md-3"><div class="small text-muted">Location</div><strong>{{ $user->location ?: 'Not specified' }}</strong></div>
        <div class="col-md-3"><div class="small text-muted">Verification</div><strong>{{ ucfirst($user->verification_status) }}</strong></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-briefcase"></i><div><div class="n">{{ $vacancies->count() }}</div><div class="l">Vacancies published</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-users"></i><div><div class="n">{{ $totals['total'] }}</div><div class="l">Applications received</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-user-check"></i><div><div class="n">{{ $filled }}/{{ $slots }}</div><div class="l">Positions filled ({{ $fillRate }}%)</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid fa-clock"></i><div><div class="n">{{ $avgDays === null ? '-' : $avgDays . 'd' }}</div><div class="l">Avg. time to decision</div></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7"><div class="panel h-100"><h2 class="h5">Applications received over time</h2>@include('partials.report-bars')</div></div>
    <div class="col-lg-5"><div class="panel h-100">
        <h2 class="h5">Hiring summary</h2>
        <div class="status-row"><span>Open vacancies now</span><strong>{{ $openNow }}</strong></div>
        <div class="status-row"><span>Approved applicants</span><strong>{{ $totals['approved'] }}</strong></div>
        <div class="status-row"><span>Rejected applicants</span><strong>{{ $totals['rejected'] }}</strong></div>
        <div class="status-row"><span>Awaiting your decision</span><strong>{{ $totals['pending'] }}</strong></div>
        <div class="status-row"><span>Approval rate (all applications)</span><strong>{{ $totals['approval_rate'] }}%</strong></div>
        <div class="status-row"><span>Skilled profiles open to you</span><strong>{{ $contactable }}</strong></div>
        <div class="progress mt-3" style="height:14px"><div class="progress-bar" style="width:{{ $fillRate }}%"></div></div>
        <div class="small text-muted mt-1">Overall positions filled: {{ $fillRate }}%</div>
    </div></div>
</div>

<div class="panel mt-4"><h2 class="h5">{{ ucfirst($period) }} statistics (applications received)</h2>@include('partials.report-table')
    <p class="small text-muted mt-2 mb-0">Vacancies posted in the same periods:
        @foreach($postedByLabel as $l => $n)<span class="chip">{{ $l }}: {{ $n }}</span>@endforeach</p></div>

<div class="panel mt-4"><h2 class="h5">Performance per vacancy</h2>
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Vacancy</th><th>Status</th><th class="text-center">Slots</th><th class="text-center">Applicants</th><th class="text-center">Approved</th><th class="text-center">Pending</th><th class="text-center">Rejected</th><th class="text-center">Remaining</th></tr></thead>
        <tbody>@forelse($vacancies as $v)
            <tr><td>{{ $v->title }}<div class="small text-muted">Deadline {{ $v->deadline->format('d M Y') }}</div></td><td><span class="badge text-bg-{{ $v->statusClass() }}">{{ $v->statusLabel() }}</span></td>
                <td class="text-center">{{ $v->slots }}</td><td class="text-center">{{ $v->applicants_count }}</td><td class="text-center">{{ $v->approved_count }}</td><td class="text-center">{{ $v->pending_count }}</td><td class="text-center">{{ $v->rejected_count }}</td><td class="text-center"><strong>{{ max(0, $v->slots - $v->approved_count) }}</strong></td></tr>
        @empty<tr><td colspan="8" class="text-muted">No published vacancies yet.</td></tr>@endforelse</tbody>
    </table></div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-4"><div class="panel h-100"><h2 class="h5">Applicants by qualification</h2>
        @forelse($byEducation as $n => $c)<div class="status-row"><span>{{ $n }}</span><strong>{{ $c }}</strong></div>@empty<p class="text-muted mb-0">No data yet.</p>@endforelse</div></div>
    <div class="col-lg-4"><div class="panel h-100"><h2 class="h5">Applicants by location</h2>
        @forelse($byLocation as $n => $c)<div class="status-row"><span>{{ $n }}</span><strong>{{ $c }}</strong></div>@empty<p class="text-muted mb-0">No data yet.</p>@endforelse</div></div>
    <div class="col-lg-4"><div class="panel h-100"><h2 class="h5">Vacancies by industry</h2>
        @forelse($byIndustry as $n => $c)<div class="status-row"><span>{{ $n }}</span><strong>{{ $c }}</strong></div>@empty<p class="text-muted mb-0">No data yet.</p>@endforelse</div></div>
</div>
<p class="small text-muted mt-3 print-only">This report was generated automatically by Connect Me from your company activity.</p>
@endsection
