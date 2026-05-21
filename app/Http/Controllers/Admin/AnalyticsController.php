<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = (int)$request->input('range', 30);
        $range = in_array($range, [7, 14, 30, 90]) ? $range : 30;
        $start = now()->subDays($range - 1)->toDateString();
        $end = now()->toDateString();

        $total = PageVisit::whereBetween('visited_date', [$start, $end])->count();
        $unique = PageVisit::whereBetween('visited_date', [$start, $end])
            ->distinct('ip')->count('ip');

        // chart
        $daily = PageVisit::select('visited_date', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$start, $end])
            ->groupBy('visited_date')
            ->orderBy('visited_date')
            ->pluck('total', 'visited_date')
            ->toArray();

        $chartLabels = [];
        $chartValues = [];
        for ($i = $range - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartValues[] = $daily[$d] ?? 0;
        }

        // breakdown
        $byPage = PageVisit::select('page_type', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$start, $end])
            ->groupBy('page_type')
            ->orderByDesc('total')
            ->get();

        $byDevice = PageVisit::select('device', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$start, $end])
            ->groupBy('device')
            ->get();

        $byReferrer = PageVisit::select('referrer', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$start, $end])
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        $byUtm = PageVisit::select('utm_source', 'utm_medium', 'utm_campaign', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$start, $end])
            ->whereNotNull('utm_source')
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        return view('admin.analytics.index', compact(
            'range', 'total', 'unique',
            'chartLabels', 'chartValues',
            'byPage', 'byDevice', 'byReferrer', 'byUtm'
        ));
    }
}
