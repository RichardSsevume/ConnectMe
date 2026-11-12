@php
    $companies = \App\Models\User::where('role', 'employer')->whereNotNull('company_name')
        ->whereHas('vacancies')->orderBy('company_name')->pluck('company_name')->unique()->values();
    $qualOptions = \App\Models\Vacancy::QUALIFICATIONS;
    $expOptions = \App\Models\Vacancy::EXPERIENCE_LEVELS;
@endphp
<form class="filter-bar" method="GET" action="{{ $action }}">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label" for="q">1. Search by Advertised Job Title</label>
            <input class="form-control" id="q" name="q" list="jobTitleList" value="{{ request('q') }}" placeholder="e.g. Accountant" autocomplete="off">
            <datalist id="jobTitleList">
                @foreach(\App\Models\Vacancy::listed()->distinct()->orderBy('title')->pluck('title') as $t)<option value="{{ $t }}">@endforeach
            </datalist></div>
        <div class="col-md-2"><label class="form-label" for="qualification">2. Qualifications</label>
            <select class="form-select" id="qualification" name="qualification">
                <option value="">Any qualification</option>
                @foreach($qualOptions as $o)<option value="{{ $o }}" @selected(request('qualification') === $o)>{{ $o }}</option>@endforeach
            </select></div>
        <div class="col-md-2"><label class="form-label" for="experience">3. Experience</label>
            <select class="form-select" id="experience" name="experience">
                <option value="">Any experience</option>
                @foreach($expOptions as $o)<option value="{{ $o }}" @selected(request('experience') === $o)>{{ $o }}</option>@endforeach
            </select></div>
        <div class="col-md-3"><label class="form-label" for="company">4. Company Name</label>
            <select class="form-select" id="company" name="company">
                <option value="">Any company</option>
                @foreach($companies as $c)<option value="{{ $c }}" @selected(request('company') === $c)>{{ $c }}</option>@endforeach
            </select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-teal"><i class="fa-solid fa-magnifying-glass"></i> 5. Apply</button></div>
    </div>
</form>
