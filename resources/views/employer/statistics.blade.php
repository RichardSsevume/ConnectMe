@extends('layouts.dashboard')
@section('title', 'Application Statistics')
@section('content')
<div class="page-head">
    <h1>Vacancy Performance</h1>
    <p>Monitor applications received for each vacancy and track your recruitment progress.</p>
</div>

@if(! $selected)
    <div class="panel"><p class="mb-0 text-muted">You have no published vacancies yet. <a href="{{ route('employer.vacancies.create') }}">Post a vacancy</a> to start receiving applications.</p></div>
@else
    <form class="filter-bar mb-4" method="GET" action="{{ route('employer.statistics') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-8"><label class="form-label" for="vacancy">Choose a vacancy</label>
                <select class="form-select" id="vacancy" name="vacancy" onchange="this.form.submit()">
                    @foreach($vacancies as $v)
                        <option value="{{ $v->id }}" @selected($v->id === $selected->id)>{{ $v->title }} ({{ $v->statusLabel() }})</option>
                    @endforeach
                </select></div>
            <div class="col-md-4 d-grid"><button class="btn btn-teal">Show Statistics</button></div>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel h-100">
                <h2 class="h5">{{ $selected->title }}</h2>
                <table class="table stat-table mb-3">
                    <thead><tr><th>Statistic</th><th class="text-end">Number</th></tr></thead>
                    <tbody>
                        <tr><td>Total Available Slots</td><td class="text-end">{{ $selected->slots }}</td></tr>
                        <tr><td>Total Applicants</td><td class="text-end">{{ $selected->applicants_count }}</td></tr>
                        <tr><td>Approved Applications</td><td class="text-end">{{ $selected->approved_count }}</td></tr>
                        <tr><td>Pending Applications</td><td class="text-end">{{ $selected->pending_count }}</td></tr>
                        <tr><td>Rejected Applications</td><td class="text-end">{{ $selected->rejected_count }}</td></tr>
                        <tr class="table-total"><td>Remaining Slots</td><td class="text-end">{{ max(0, $selected->slots - $selected->approved_count) }}</td></tr>
                    </tbody>
                </table>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('employer.applicants', ['vacancy' => $selected, 'status' => 'pending']) }}">View Pending Applications</a>
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('employer.applicants', ['vacancy' => $selected, 'status' => 'approved']) }}">View Approved Applicants</a>
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('employer.applicants', ['vacancy' => $selected, 'status' => 'rejected']) }}">View Rejected Applications</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel h-100">
                <h2 class="h5">Slots Filled</h2>
                @php $pct = $selected->slots > 0 ? min(100, round($selected->approved_count / $selected->slots * 100)) : 0; @endphp
                <div class="progress mb-2" style="height:14px" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width:{{ $pct }}%"></div></div>
                <p class="text-muted">{{ $selected->approved_count }} of {{ $selected->slots }} positions filled ({{ $pct }}%).</p>
                <div class="alert alert-info mb-0"><i class="fa-solid fa-circle-info"></i> When all available positions have been filled, no further applicants can be approved.</div>
            </div>
        </div>
    </div>

    <div class="panel mt-4">
        <h2 class="h5">All Vacancies at a Glance</h2>
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Vacancy</th><th class="text-center">Slots</th><th class="text-center">Applicants</th><th class="text-center">Approved</th><th class="text-center">Pending</th><th class="text-center">Rejected</th><th class="text-center">Remaining</th></tr></thead>
            <tbody>@foreach($vacancies as $v)
                <tr><td><a href="{{ route('employer.statistics', ['vacancy' => $v->id]) }}">{{ $v->title }}</a></td>
                    <td class="text-center">{{ $v->slots }}</td><td class="text-center">{{ $v->applicants_count }}</td><td class="text-center">{{ $v->approved_count }}</td>
                    <td class="text-center">{{ $v->pending_count }}</td><td class="text-center">{{ $v->rejected_count }}</td><td class="text-center"><strong>{{ max(0, $v->slots - $v->approved_count) }}</strong></td></tr>
            @endforeach</tbody>
        </table></div>
    </div>
@endif
@endsection
