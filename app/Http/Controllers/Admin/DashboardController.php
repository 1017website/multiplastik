<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\News;
use App\Models\PageVisit;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $last7Start = now()->subDays(6)->toDateString();
        $last30Start = now()->subDays(29)->toDateString();

        $stats = [
            'visit_today' => PageVisit::where('visited_date', $today)->count(),
            'visit_7d' => PageVisit::whereBetween('visited_date', [$last7Start, $today])->count(),
            'visit_30d' => PageVisit::whereBetween('visited_date', [$last30Start, $today])->count(),
            'total_brands' => Brand::count(),
            'total_products' => Product::count(),
            'total_news' => News::count(),
        ];

        // Chart 7 hari terakhir
        $chartData = PageVisit::select('visited_date', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$last7Start, $today])
            ->groupBy('visited_date')
            ->orderBy('visited_date')
            ->pluck('total', 'visited_date')
            ->toArray();

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartValues[] = $chartData[$d] ?? 0;
        }

        // Top source
        $topSources = PageVisit::select('utm_source', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$last30Start, $today])
            ->whereNotNull('utm_source')
            ->groupBy('utm_source')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $deviceStats = PageVisit::select('device', DB::raw('COUNT(*) as total'))
            ->whereBetween('visited_date', [$last30Start, $today])
            ->groupBy('device')
            ->pluck('total', 'device')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartValues', 'topSources', 'deviceStats'));
    }
}
