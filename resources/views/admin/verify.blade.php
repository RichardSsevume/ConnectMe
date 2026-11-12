@extends('layouts.dashboard')
@section('title', 'Verify Accounts')
@section('content')
<div class="page-head">
    <h1>Account Verification</h1>
    <p>Review and verify youth and employer accounts to promote trust and reliability on Connect Me.</p>
</div>
<div class="panel">
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>User Name</th><th>Account Type</th><th>Email Address</th><th>Registration Date</th><th>Verification Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($users as $u)
            <tr>
                <td><div class="d-flex gap-2 align-items-center">@include('partials.avatar', ['u' => $u, 'size' => 36]) {{ $u->displayName() }}</div></td>
                <td>{{ $u->isEmployer() ? 'Employer' : 'Skilled Youth' }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->created_at->format('d/m/Y') }}</td>
                <td>@include('partials.vstatus', ['u' => $u])</td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('admin.users.show', $u) }}">View Details</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.verification', $u) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="verify">
                        <button class="btn btn-success btn-sm" @disabled($u->verification_status === 'verified')>Verify Account</button></form>
                    <form class="d-inline" method="POST" action="{{ route('admin.verification', $u) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="reject">
                        <button class="btn btn-outline-danger btn-sm" @disabled($u->verification_status === 'rejected')>Reject Verification</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
