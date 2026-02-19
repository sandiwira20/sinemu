<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use App\Models\LostReport;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $itemCount   = Item::count();
        $openClaims  = Claim::where('status', 'pending')->count();
        $openReports = LostReport::where('status', 'open')->count();

        $categories = Category::select('id', 'name')->orderBy('name')->get();

        $reportHistoryRaw = LostReport::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->orderBy('ym', 'asc')
            ->get();

        $reportHistoryByCategoryRaw = LostReport::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total, category_id")
            ->groupBy('ym', 'category_id')
            ->orderBy('ym', 'asc')
            ->get();

        $reportHistory = $reportHistoryRaw->map(function ($row) {
            $label = \Carbon\Carbon::createFromFormat('Y-m', $row->ym)->translatedFormat('M Y');
            return ['label' => $label, 'total' => (int) $row->total];
        })->values();

        $reportHistoryByCategory = $reportHistoryByCategoryRaw->groupBy('category_id')->map(function ($items, $categoryId) {
            return $items->map(function ($row) {
                $label = \Carbon\Carbon::createFromFormat('Y-m', $row->ym)->translatedFormat('M Y');
                return ['label' => $label, 'total' => (int) $row->total];
            })->values();
        });

        $latestItems = Item::with('category')->latest()->take(5)->get();
        $latestReports = LostReport::with(['user', 'category'])->latest()->take(5)->get();
        $latestClaims  = Claim::with(['user', 'item'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'itemCount',
            'openClaims',
            'openReports',
            'categories',
            'reportHistory',
            'reportHistoryByCategory',
            'latestItems',
            'latestReports',
            'latestClaims'
        ));
    }
}
