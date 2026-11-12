@if($vacancies->count())
    <div class="row g-4">
        @foreach($vacancies as $vacancy)
            <div class="col-md-6 col-xl-4">@include('partials.job-card', ['vacancy' => $vacancy, 'applied' => $applied ?? collect()])</div>
        @endforeach
    </div>
    <div class="mt-4">{{ $vacancies->links() }}</div>
@else
    @include('partials.empty-jobs', ['variant' => $variant ?? 'public'])
@endif
