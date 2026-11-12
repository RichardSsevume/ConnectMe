@extends('layouts.dashboard')
@section('title', 'My Applications')
@section('content')
<div class="page-head">
    <h1>My Applications</h1>
    <p>View all the vacancies you have applied for and monitor your application progress.</p>
</div>
<div class="panel">
    @if($applications->isEmpty())
        <p class="mb-0 text-muted">You have not applied for any vacancy yet. <a href="{{ route('youth.jobs') }}">Browse jobs</a> to find your next opportunity.</p>
    @else
        <div class="table-responsive"><table class="table align-middle mb-0">
            <thead><tr><th>Job Title</th><th>Company</th><th>Date Applied</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @foreach($applications as $a)
                <tr>
                    <td>{{ $a->vacancy->title }}</td>
                    <td>{{ $a->vacancy->employer->displayName() }}</td>
                    <td>{{ $a->created_at->format('d/m/Y') }}</td>
                    <td><span class="badge text-bg-{{ $a->statusClass() }}">{{ ucfirst($a->status) }}</span></td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-outline-navy btn-sm" href="{{ route('youth.applications.show', $a) }}">View Application</a>
                        @if($a->status === 'pending')
                            <form class="d-inline" method="POST" action="{{ route('youth.withdraw', $a) }}" onsubmit="return confirm('Withdraw this application?')">@csrf @method('PATCH')
                                <button class="btn btn-outline-danger btn-sm">Withdraw Application</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
