<div class="btn-group report-tabs no-print" role="group" aria-label="Report period">
    @foreach(['weekly' => 'Weekly', 'monthly' => 'Monthly', 'annual' => 'Annual'] as $k => $l)
        <a class="btn btn-sm {{ $period === $k ? 'btn-teal' : 'btn-outline-navy' }}" href="{{ request()->fullUrlWithQuery(['period' => $k]) }}">{{ $l }}</a>
    @endforeach
</div>
