@php
    $ap = ($applied ?? collect())->get($vacancy->id);
    $emp = $vacancy->employer;
@endphp
<article class="job-card h-100">
    <div class="d-flex gap-3 align-items-center mb-3">
        @include('partials.avatar', ['u' => $emp, 'size' => 48])
        <div>
            <h3 class="job-title">{{ $vacancy->title }}</h3>
            <div class="job-company">{{ $emp->displayName() }} @include('partials.verified', ['u' => $emp])</div>
        </div>
    </div>
    <ul class="job-meta">
        <li><i class="fa-solid fa-location-dot"></i> {{ $vacancy->location }}</li>
        <li><i class="fa-solid fa-screwdriver-wrench"></i> <span><strong>Required skills:</strong> {{ $vacancy->required_skills }}</span></li>
        <li><i class="fa-solid fa-users"></i> <span><strong>Available positions:</strong> {{ $vacancy->slotsLeft() }}</span></li>
        <li><i class="fa-regular fa-calendar"></i> <span><strong>Deadline:</strong> {{ $vacancy->deadline->format('d M Y') }}</span></li>
    </ul>
    <p class="job-desc">{{ \Illuminate\Support\Str::limit($vacancy->description, 110) }}</p>
    <div class="d-flex gap-2 flex-wrap mt-auto">
        <a class="btn btn-outline-navy btn-sm" href="{{ route('jobs.show', $vacancy) }}">View Details</a>
        @guest
            <a class="btn btn-teal btn-sm" href="{{ route('login') }}">Apply Now</a>
        @endguest
        @auth
            @if(auth()->user()->isYouth())
                @if($ap && $ap !== 'withdrawn')
                    <span class="badge text-bg-{{ \App\Models\Application::STATUS_CLASS[$ap] ?? 'secondary' }} align-self-center">Applied: {{ ucfirst($ap) }}</span>
                @elseif($vacancy->slotsLeft() > 0)
                    <form method="POST" action="{{ route('youth.apply', $vacancy) }}">@csrf
                        <button class="btn btn-teal btn-sm">Apply Now</button>
                    </form>
                @else
                    <span class="badge text-bg-warning align-self-center">Positions filled</span>
                @endif
            @endif
        @endauth
    </div>
</article>
