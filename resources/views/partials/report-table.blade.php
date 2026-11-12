<div class="table-responsive"><table class="table align-middle mb-0 report-table">
    <thead><tr><th>{{ $first ?? 'Period' }}</th><th class="text-center">Applications</th><th class="text-center">Approved</th><th class="text-center">Rejected</th><th class="text-center">Pending</th><th class="text-center">Withdrawn</th></tr></thead>
    <tbody>
        @foreach($rows as $r)
            <tr><td>{{ $r['label'] }}</td><td class="text-center">{{ $r['total'] }}</td><td class="text-center">{{ $r['approved'] }}</td><td class="text-center">{{ $r['rejected'] }}</td><td class="text-center">{{ $r['pending'] }}</td><td class="text-center">{{ $r['withdrawn'] }}</td></tr>
        @endforeach
        <tr class="table-total"><td>Total</td><td class="text-center">{{ $totals['total'] }}</td><td class="text-center">{{ $totals['approved'] }}</td><td class="text-center">{{ $totals['rejected'] }}</td><td class="text-center">{{ $totals['pending'] }}</td><td class="text-center">{{ $totals['withdrawn'] }}</td></tr>
    </tbody>
</table></div>
