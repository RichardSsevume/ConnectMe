@extends('layouts.dashboard')
@section('title', 'Manage Users')
@section('content')
<div class="page-head">
    <h1>User Management</h1>
    <p>View and manage all registered users on the platform.</p>
</div>
<div class="panel">
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Full Name</th><th>Email Address</th><th>Account Type</th><th>Registration Date</th><th>Verification Status</th><th>Account Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->name }}@if($u->isEmployer())<div class="small text-muted">{{ $u->company_name }}</div>@endif</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->isEmployer() ? 'Employer' : 'Skilled Youth' }}</td>
                <td>{{ $u->created_at->format('d/m/Y') }}</td>
                <td>@include('partials.vstatus', ['u' => $u])</td>
                <td><span class="badge text-bg-{{ $u->account_status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($u->account_status) }}</span></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-outline-navy btn-sm" href="{{ route('admin.users.show', $u) }}">View User</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.users.status', $u) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="suspend">
                        <button class="btn btn-outline-danger btn-sm" @disabled($u->account_status === 'suspended')>Suspend Account</button></form>
                    <form class="d-inline" method="POST" action="{{ route('admin.users.status', $u) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="activate">
                        <button class="btn btn-outline-success btn-sm" @disabled($u->account_status === 'active')>Activate Account</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
