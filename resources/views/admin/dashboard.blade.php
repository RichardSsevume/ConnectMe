@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
<div class="welcome">
    <h1>Welcome to the Connect Me Administration Panel</h1>
    <p>Manage users, verify accounts and monitor activities across the platform.</p>
    <a class="btn btn-light cta-btn" href="{{ route('admin.verify') }}"><i class="fa-solid fa-user-check"></i> Verify Accounts</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg"><div class="tile"><i class="fa-solid fa-user-graduate"></i><div><div class="n">{{ $stats['youth'] }}</div><div class="l">Skilled youth</div></div></div></div>
    <div class="col-6 col-lg"><div class="tile"><i class="fa-solid fa-building"></i><div><div class="n">{{ $stats['employers'] }}</div><div class="l">Employers</div></div></div></div>
    <div class="col-6 col-lg"><div class="tile"><i class="fa-solid fa-hourglass-half"></i><div><div class="n">{{ $stats['pending'] }}</div><div class="l">Awaiting verification</div></div></div></div>
    <div class="col-6 col-lg"><div class="tile"><i class="fa-solid fa-briefcase"></i><div><div class="n">{{ $stats['vacancies'] }}</div><div class="l">Vacancies</div></div></div></div>
    <div class="col-6 col-lg"><div class="tile"><i class="fa-solid fa-file-lines"></i><div><div class="n">{{ $stats['applications'] }}</div><div class="l">Applications</div></div></div></div>
</div>
<div class="panel">
    <h2 class="h5">Recent Messages from the Contact Form</h2>
    @forelse($messages as $m)
        <div class="status-row"><div><strong>{{ $m->subject }}</strong><div class="small text-muted">{{ $m->name }} &middot; {{ $m->email }} &middot; {{ $m->created_at->format('d/m/Y H:i') }}</div>
            <div class="small">{{ \Illuminate\Support\Str::limit($m->message, 180) }}</div></div></div>
    @empty
        <p class="text-muted mb-0">No messages yet.</p>
    @endforelse
</div>
@endsection
