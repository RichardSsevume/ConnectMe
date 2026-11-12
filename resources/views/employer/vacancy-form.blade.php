@extends('layouts.dashboard')
@section('title', $vacancy->exists ? 'Edit Vacancy' : 'Post Vacancy')
@section('content')
<div class="page-head">
    <h1>{{ $vacancy->exists ? 'Edit Vacancy' : 'Advertise a Job Vacancy' }}</h1>
    <p>Looking for new talent? Publish your job opportunity and connect with skilled individuals.</p>
</div>
<div class="panel">
    <form method="POST" action="{{ $vacancy->exists ? route('employer.vacancies.update', $vacancy) : route('employer.vacancies.store') }}">@csrf
        @if($vacancy->exists) @method('PUT') @endif
        <div class="row">
            <div class="col-md-8 mb-3"><label class="form-label" for="title">Job Title</label>
                <input class="form-control" id="title" name="title" value="{{ old('title', $vacancy->title) }}" required></div>
            <div class="col-md-4 mb-3"><label class="form-label" for="category">Job Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select category</option>
                    @foreach(\App\Models\Vacancy::INDUSTRIES as $industry)
                        <option value="{{ $industry }}" @selected(old('category', $vacancy->category) === $industry)>{{ $industry }}</option>
                    @endforeach
                </select></div>
        </div>
        <div class="mb-3"><label class="form-label" for="description">Job Description</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $vacancy->description) }}</textarea></div>
        <div class="mb-3"><label class="form-label" for="required_skills">Required Skills</label>
            <input class="form-control" id="required_skills" name="required_skills" value="{{ old('required_skills', $vacancy->required_skills) }}" placeholder="Separate with commas, e.g. Excel, Communication" required></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label" for="qualifications">Academic Qualifications</label>
                <input class="form-control" id="qualifications" name="qualifications" value="{{ old('qualifications', $vacancy->qualifications) }}" placeholder="e.g. Diploma in Accounting"></div>
            <div class="col-md-6 mb-3"><label class="form-label" for="experience">Experience Required</label>
                <input class="form-control" id="experience" name="experience" value="{{ old('experience', $vacancy->experience) }}" placeholder="e.g. 1 - 2 years"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label" for="location">Job Location</label>
                <input class="form-control" id="location" name="location" value="{{ old('location', $vacancy->location) }}" required></div>
            <div class="col-md-4 mb-3"><label class="form-label" for="employment_type">Employment Type</label>
                <select class="form-select" id="employment_type" name="employment_type" required>
                    <option value="">Select type</option>
                    @foreach(\App\Models\Vacancy::TYPES as $type)
                        <option value="{{ $type }}" @selected(old('employment_type', $vacancy->employment_type) === $type)>{{ $type }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-2 mb-3"><label class="form-label" for="slots">Number of Available Positions</label>
                <input class="form-control" id="slots" type="number" min="1" name="slots" value="{{ old('slots', $vacancy->slots) }}" required></div>
            <div class="col-md-2 mb-3"><label class="form-label" for="deadline">Application Deadline</label>
                <input class="form-control" id="deadline" type="date" name="deadline" value="{{ old('deadline', optional($vacancy->deadline)->format('Y-m-d')) }}" min="{{ now()->toDateString() }}" required></div>
        </div>
        <div class="mb-4"><label class="form-label" for="contact_info">Contact Information</label>
            <input class="form-control" id="contact_info" name="contact_info" value="{{ old('contact_info', $vacancy->contact_info) }}" placeholder="Email and/or phone number for applicants" required></div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-teal btn-lg" name="action" value="publish">Publish Vacancy</button>
            <button class="btn btn-outline-navy btn-lg" name="action" value="draft">Save as Draft</button>
            <a class="btn btn-link" href="{{ route('employer.vacancies') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
