@extends('layouts.dashboard')
@section('title', 'Manage Vacancies')
@section('content')
<div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div><h1>Manage Your Vacancies</h1><p>View and manage all vacancies advertised by your organisation.</p></div>
    <a class="btn btn-teal" href="{{ route('employer.vacancies.create') }}"><i class="fa-solid fa-plus"></i> Post Vacancy</a>
</div>
<div class="panel">
    @if($vacancies->isEmpty())
        <p class="mb-0 text-muted">You have not posted any vacancy yet. <a href="{{ route('employer.vacancies.create') }}">Post your first vacancy</a>.</p>
    @else
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Job Title</th><th class="text-center">Slots</th><th class="text-center">Applicants</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @foreach($vacancies as $v)
                <tr>
                    <td>{{ $v->title }}<div class="small text-muted">Deadline: {{ $v->deadline->format('d M Y') }}</div></td>
                    <td class="text-center">{{ $v->slots }}</td>
                    <td class="text-center">{{ $v->applicants_count }}</td>
                    <td><span class="badge text-bg-{{ $v->statusClass() }}">{{ $v->statusLabel() }}</span></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end flex-wrap">
                            <a class="btn btn-outline-navy btn-sm" href="{{ route('employer.vacancies.edit', $v) }}">Edit Vacancy</a>
                            <a class="btn btn-teal btn-sm" href="{{ route('employer.applicants', $v) }}">View Applicants</a>
                            @if($v->status !== 'draft')
                                <form method="POST" action="{{ route('employer.vacancies.toggle', $v) }}">@csrf @method('PATCH')
                                    <button class="btn btn-outline-secondary btn-sm">{{ $v->status === 'open' ? 'Close Vacancy' : 'Reopen Vacancy' }}</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('employer.vacancies.destroy', $v) }}" onsubmit="return confirm('Delete this vacancy and all its applications?')">@csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Delete Vacancy</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
