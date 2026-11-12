@extends('layouts.dashboard')
@section('title', 'User Details')
@section('content')
<a href="{{ route('admin.users') }}" class="small"><i class="fa-solid fa-arrow-left"></i> Back to Manage Users</a>
<div class="panel mt-2">
    <div class="d-flex gap-4 align-items-center flex-wrap mb-4">
        @include('partials.avatar', ['u' => $user, 'size' => 96])
        <div>
            <h1 class="h3 mb-1">{{ $user->displayName() }} @include('partials.vstatus', ['u' => $user])</h1>
            <div class="text-muted">{{ $user->isEmployer() ? 'Employer' : 'Skilled Youth' }} &middot; Registered {{ $user->created_at->format('d/m/Y') }}</div>
            <div><span class="badge text-bg-{{ $user->account_status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($user->account_status) }}</span></div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><strong>Name:</strong> {{ $user->name }}</div>
        <div class="col-md-6"><strong>Email:</strong> {{ $user->email }}</div>
        <div class="col-md-6"><strong>Phone:</strong> {{ $user->phone ?: 'Not provided' }}</div>
        <div class="col-md-6"><strong>Location:</strong> {{ $user->location ?: 'Not provided' }}</div>
        @if($user->isEmployer())
            <div class="col-md-6"><strong>Company:</strong> {{ $user->company_name }}</div>
            <div class="col-md-6"><strong>Industry:</strong> {{ $user->industry }}</div>
            <div class="col-md-6"><strong>Website:</strong> {{ $user->website ?: 'Not provided' }}</div>
            <div class="col-md-6"><strong>Vacancies posted:</strong> {{ $user->vacancies_count }}</div>
            <div class="col-12"><strong>Description:</strong> {{ $user->company_description ?: 'Not provided' }}</div>
        @else
            <div class="col-md-6"><strong>Professional title:</strong> {{ $user->professional_title ?: 'Not provided' }}</div>
            <div class="col-md-6"><strong>Education:</strong> {{ $user->education_level ?: 'Not provided' }}</div>
            <div class="col-md-6"><strong>Applications made:</strong> {{ $user->applications_count }}</div>
            <div class="col-md-6"><strong>Visible to employers:</strong> {{ $user->visible_to_employers ? 'Yes' : 'No' }}</div>
            <div class="col-12"><strong>Skills:</strong> {{ $user->skills ?: 'Not provided' }}</div>
            <div class="col-12"><strong>Biography:</strong> {{ $user->bio ?: 'Not provided' }}</div>
            <div class="col-12"><strong>Documents:</strong>
                @forelse($user->documents as $doc)<a class="me-3" href="{{ $doc->url() }}" target="_blank" rel="noopener"><i class="fa-solid fa-paperclip"></i> {{ \App\Models\Document::TYPES[$doc->type][0] ?? $doc->type }}</a>@empty None uploaded @endforelse</div>
        @endif
    </div>
    <hr>
    <div class="d-flex gap-2 flex-wrap">
        <form method="POST" action="{{ route('admin.verification', $user) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="verify">
            <button class="btn btn-success" @disabled($user->verification_status === 'verified')>Verify Account</button></form>
        <form method="POST" action="{{ route('admin.verification', $user) }}">@csrf @method('PATCH')<input type="hidden" name="action" value="reject">
            <button class="btn btn-outline-danger" @disabled($user->verification_status === 'rejected')>Reject Verification</button></form>
        <form method="POST" action="{{ route('admin.users.status', $user) }}">@csrf @method('PATCH')
            <input type="hidden" name="action" value="{{ $user->account_status === 'active' ? 'suspend' : 'activate' }}">
            <button class="btn btn-outline-navy">{{ $user->account_status === 'active' ? 'Suspend Account' : 'Activate Account' }}</button></form>
    </div>
</div>
@endsection
