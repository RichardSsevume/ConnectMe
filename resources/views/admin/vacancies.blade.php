@extends('layouts.dashboard')
@section('title', 'Manage Vacancies')
@section('content')
<div class="page-head">
    <h1>Vacancy Management</h1>
    <p>Monitor vacancies published by employers and ensure that job advertisements comply with platform guidelines.</p>
</div>
<div class="panel">
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Job Title</th><th>Employer Name</th><th class="text-center">Number of Slots</th><th>Date Posted</th><th>Application Deadline</th><th>Vacancy Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($vacancies as $v)
            <tr>
                <td>{{ $v->title }}</td>
                <td>{{ $v->employer->displayName() }}</td>
                <td class="text-center">{{ $v->slots }}</td>
                <td>{{ $v->created_at->format('d/m/Y') }}</td>
                <td>{{ $v->deadline->format('d/m/Y') }}</td>
                <td><span class="badge text-bg-{{ $v->statusClass() }}">{{ $v->statusLabel() }}</span></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('jobs.show', $v) }}">View Vacancy</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.vacancies.moderate', $v) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="approve">
                        <button class="btn btn-success btn-sm" @disabled($v->moderation === 'approved')>Approve Vacancy</button></form>
                    <form class="d-inline" method="POST" action="{{ route('admin.vacancies.moderate', $v) }}" onsubmit="return confirm('Remove this vacancy from public view?')">@csrf @method('PATCH')<input type="hidden" name="action" value="remove">
                        <button class="btn btn-outline-danger btn-sm" @disabled($v->moderation === 'removed')>Remove Vacancy</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <div class="mt-3">{{ $vacancies->links() }}</div>
</div>
@endsection
