@extends('layouts.dashboard')
@section('title', 'Contact Messages')
@section('content')
<div class="page-head"><h1>Contact Messages</h1><p>Messages sent through the Contact Us form.</p></div>
<div class="panel">
    @forelse($messages as $m)
        <div class="status-row align-items-start">
            <div><strong>{{ $m->subject }}</strong>
                <div class="small text-muted">{{ $m->name }} &middot; <a href="mailto:{{ $m->email }}">{{ $m->email }}</a> &middot; {{ $m->created_at->format('d/m/Y H:i') }}</div>
                <div class="mt-1">{{ $m->message }}</div></div>
            <form method="POST" action="{{ route('admin.messages.delete', $m) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')
                <button class="btn btn-outline-navy btn-sm"><i class="fa-solid fa-trash"></i></button></form>
        </div>
    @empty<p class="text-muted mb-0">No messages yet.</p>@endforelse
</div>
<div class="mt-3">{{ $messages->links() }}</div>
@endsection
