@extends('layouts.dashboard')
@section('title', 'Company Profile')
@section('content')
<div class="page-head">
    <h1>Company Profile</h1>
    <p>Manage your organisation's information to help job seekers understand your business.</p>
</div>
<div class="panel">
    <form method="POST" action="{{ route('employer.company.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('partials.avatar', ['u' => $user, 'size' => 96])
            <div><label class="form-label" for="profile_photo">Company Logo</label>
                <input class="form-control" id="profile_photo" type="file" name="profile_photo" accept="image/*">
                <div class="form-text">JPG or PNG, up to 2 MB.</div></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label" for="company_name">Company Name</label>
                <input class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="industry">Industry</label>
                <select class="form-select" id="industry" name="industry" required>
                    <option value="">Select industry</option>
                    @foreach(\App\Models\Vacancy::INDUSTRIES as $industry)
                        <option value="{{ $industry }}" @selected(old('industry', $user->industry) === $industry)>{{ $industry }}</option>
                    @endforeach
                </select></div>
        </div>
        <div class="mb-3"><label class="form-label" for="company_description">Company Description</label>
            <textarea class="form-control" id="company_description" name="company_description" rows="4">{{ old('company_description', $user->company_description) }}</textarea></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label" for="email">Email Address</label>
                <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="phone">Phone Number</label>
                <input class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="location">Location</label>
                <input class="form-control" id="location" name="location" value="{{ old('location', $user->location) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="website">Website</label>
                <input class="form-control" id="website" name="website" value="{{ old('website', $user->website) }}" placeholder="https://"></div>
        </div>
        <button class="btn btn-teal btn-lg">Update Company Profile</button>
    </form>
</div>
@endsection
