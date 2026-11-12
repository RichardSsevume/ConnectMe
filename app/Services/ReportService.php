<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Group a collection of models by weekly / monthly / annual period using created_at.
     * Returns rows: [label, total, approved, rejected, pending, withdrawn].
     */
    public static function buckets(Collection $items, string $period): array
    {
        [$count, $keyFn, $labelFn, $stepFn] = match ($period) {
            'weekly' => [8, fn (Carbon $d) => $d->copy()->startOfWeek()->format('Y-m-d'),
                fn (Carbon $d) => $d->copy()->startOfWeek()->format('d M') . ' - ' . $d->copy()->endOfWeek()->format('d M'),
                fn (Carbon $d, int $i) => $d->copy()->startOfWeek()->subWeeks($i)],
            'annual' => [5, fn (Carbon $d) => $d->format('Y'), fn (Carbon $d) => $d->format('Y'),
                fn (Carbon $d, int $i) => $d->copy()->startOfYear()->subYears($i)],
            default => [12, fn (Carbon $d) => $d->format('Y-m'), fn (Carbon $d) => $d->format('M Y'),
                fn (Carbon $d, int $i) => $d->copy()->startOfMonth()->subMonths($i)],
        };

        $grouped = $items->groupBy(fn ($m) => $keyFn($m->created_at));
        $rows = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $d = $stepFn(now(), $i);
            $g = $grouped->get($keyFn($d), collect());
            $rows[] = [
                'label' => $labelFn($d),
                'total' => $g->count(),
                'approved' => $g->where('status', 'approved')->count(),
                'rejected' => $g->where('status', 'rejected')->count(),
                'pending' => $g->where('status', 'pending')->count(),
                'withdrawn' => $g->where('status', 'withdrawn')->count(),
            ];
        }

        return $rows;
    }

    public static function totals(array $rows): array
    {
        $t = ['total' => 0, 'approved' => 0, 'rejected' => 0, 'pending' => 0, 'withdrawn' => 0];
        foreach ($rows as $r) {
            foreach ($t as $k => $_) {
                $t[$k] += $r[$k];
            }
        }
        $decided = $t['approved'] + $t['rejected'];
        $t['success_rate'] = $decided > 0 ? round($t['approved'] / $decided * 100) : null;
        $t['approval_rate'] = $t['total'] > 0 ? round($t['approved'] / $t['total'] * 100) : 0;

        return $t;
    }

    public static function period(?string $p): string
    {
        return in_array($p, ['weekly', 'monthly', 'annual'], true) ? $p : 'monthly';
    }

    public static function periodTitle(string $p): string
    {
        return ['weekly' => 'Weekly (last 8 weeks)', 'monthly' => 'Monthly (last 12 months)', 'annual' => 'Annual (last 5 years)'][$p];
    }
}
