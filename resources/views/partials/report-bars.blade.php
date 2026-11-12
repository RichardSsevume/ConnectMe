@php $max = max(1, collect($rows)->max('total')); @endphp
<div class="bar-chart" role="img" aria-label="Applications per period">
    @foreach($rows as $r)
        <div class="bar-col" title="{{ $r['label'] }}: {{ $r['total'] }}">
            <div class="bar-val">{{ $r['total'] }}</div>
            <div class="bar" style="height:{{ max(3, round($r['total'] / $max * 100)) }}%"></div>
            <div class="bar-lab">{{ $r['label'] }}</div>
        </div>
    @endforeach
</div>
