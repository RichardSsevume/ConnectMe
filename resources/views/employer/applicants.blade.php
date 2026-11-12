@extends('layouts.dashboard')
@section('title', 'Applicants')
@section('content')
<a href="{{ route('employer.vacancies') }}" class="small"><i class="fa-solid fa-arrow-left"></i> Back to Manage Vacancies</a>
<div class="page-head mt-2">
    <h1>{{ $vacancy->title }}</h1>
    <p>Positions: {{ $vacancy->slots }} &middot; Approved: {{ $vacancy->approvedCount() }} &middot; <strong>Remaining slots: {{ $vacancy->slotsLeft() }}</strong></p>
</div>
<div class="d-flex gap-2 flex-wrap mb-3">
    @foreach([['', 'All'], ['pending', 'Pending'], ['approved', 'Approved'], ['rejected', 'Rejected']] as [$key, $label])
        <a class="btn btn-sm {{ (string) ($filter ?? '') === $key ? 'btn-navy' : 'btn-outline-navy' }}" href="{{ route('employer.applicants', array_filter(['vacancy' => $vacancy, 'status' => $key])) }}">{{ $label }}</a>
    @endforeach
</div>
<div class="panel">
    @if($applications->isEmpty())
        <p class="text-muted mb-0">No applications to show.</p>
    @else
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Applicant</th><th>Skills</th><th>Note &amp; documents</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @foreach($applications as $a)
                @php $p = $a->applicant; @endphp
                <tr>
                    <td>
                        <div class="d-flex gap-2 align-items-center">@include('partials.avatar', ['u' => $p, 'size' => 40])
                            <div><a href="{{ route('employer.youth.show', $p) }}">{{ $p->name }}</a> @include('partials.verified', ['u' => $p])
                                <div class="small text-muted">{{ $p->professional_title }}</div></div></div>
                    </td>
                    <td class="small">{{ $p->skills }}</td>
                    <td class="small">
                        {{ $a->cover_note }}
                        @foreach($p->documents as $doc)<div><a href="{{ $doc->url() }}" target="_blank" rel="noopener"><i class="fa-solid fa-paperclip"></i> {{ \App\Models\Document::TYPES[$doc->type][0] ?? $doc->type }}</a></div>@endforeach
                    </td>
                    <td><span class="badge text-bg-{{ $a->statusClass() }}">{{ ucfirst($a->status) }}</span></td>
                    <td class="text-end text-nowrap">
                        @if($a->status !== 'approved')
                            <form class="d-inline" method="POST" action="{{ route('employer.decide', $a) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="approved">
                                <button class="btn btn-outline-success btn-sm">Approve</button></form>
                        @endif
                        @if($a->status !== 'rejected')
                            <form class="d-inline" method="POST" action="{{ route('employer.decide', $a) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="rejected">
                                <button class="btn btn-outline-danger btn-sm">Reject</button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
