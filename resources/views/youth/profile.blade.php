@extends('layouts.dashboard')
@section('title', 'My Profile')
@section('content')
<div class="page-head">
    <h1>My Profile</h1>
    <p>Create a professional profile that helps employers understand your skills, experience and qualifications.</p>
</div>
<div class="panel">
    <form method="POST" action="{{ route('youth.profile.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('partials.avatar', ['u' => $user, 'size' => 96])
            <div><label class="form-label" for="profile_photo">Profile Picture</label>
                <input class="form-control" id="profile_photo" type="file" name="profile_photo" accept="image/*">
                <div class="form-text">JPG or PNG, up to 2 MB.</div></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label" for="name">Full Name</label>
                <input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="email">Email Address</label>
                <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="phone">Phone Number</label>
                <input class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="location">Location</label>
                <input class="form-control" id="location" name="location" value="{{ old('location', $user->location) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="professional_title">Professional Title</label>
                <input class="form-control" id="professional_title" name="professional_title" value="{{ old('professional_title', $user->professional_title) }}" placeholder="e.g. Graphic Designer"></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="education_level">Education Level</label>
                <select class="form-select" id="education_level" name="education_level">
                    <option value="">Select education level</option>
                    @foreach(\App\Models\User::EDUCATION_LEVELS as $level)
                        <option value="{{ $level }}" @selected(old('education_level', $user->education_level) === $level)>{{ $level }}</option>
                    @endforeach
                </select></div>
        </div>
        <div class="mb-3"><label class="form-label" for="skills">Skills</label>
            <input class="form-control" id="skills" name="skills" value="{{ old('skills', $user->skills) }}" placeholder="Separate with commas, e.g. Graphic design, Canva, Photoshop"></div>
        <div class="mb-3"><label class="form-label" for="bio">About Me / Biography</label>
            <textarea class="form-control" id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea></div>
        <div class="mb-3"><label class="form-label" for="work_experience">Work Experience</label>
            <textarea class="form-control" id="work_experience" name="work_experience" rows="4">{{ old('work_experience', $user->work_experience) }}</textarea></div>
        <div class="mb-3"><label class="form-label" for="portfolio_url">Portfolio Link</label>
            <input class="form-control" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $user->portfolio_url) }}" placeholder="https://"></div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="visible_to_employers" value="1" id="visible" @checked(old('visible_to_employers', $user->visible_to_employers))>
            <label class="form-check-label" for="visible">Allow employers to view my profile</label>
            <div class="form-text">When ticked, logged-in employers can find your profile and contact you. Untick it to hide your profile.</div>
        </div>
        <button class="btn btn-teal btn-lg">Save Profile</button>
    </form>
</div>
@endsection
