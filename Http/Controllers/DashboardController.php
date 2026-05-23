<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display modern dashboard
     */
    public function index()
    {
        // 1. Core Statistics Cards
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_price');
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();

        // 2. Notifications & Recent Activities
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Chart Data: Weekly Sales Trend (Last 7 Days)
        $salesTrend = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays($i)->isoFormat('dddd');
            
            $totalSale = Transaction::whereDate('created_at', $date)->sum('total_price');
            
            $salesTrend[] = (float) $totalSale;
            $dates[] = $displayDate;
        }

        // 4. Chart Data: Stock Distribution by Category
        $categoriesWithCount = Category::withCount('products')->get();
        $categoryNames = $categoriesWithCount->pluck('name')->toArray();
        $categoryProductCounts = $categoriesWithCount->pluck('products_count')->toArray();

        // 5. Recent Activity Logs (combining stock transactions)
        $recentStockIns = StockIn::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'in',
                    'title' => 'Stok Masuk',
                    'desc' => "{$item->product->name} (+{$item->quantity} pcs)",
                    'user' => $item->user->name,
                    'time' => $item->created_at->diffForHumans()
                ];
            });

        $recentStockOuts = StockOut::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'out',
                    'title' => 'Stok Keluar',
                    'desc' => "{$item->product->name} (-{$item->quantity} pcs)",
                    'user' => $item->user->name,
                    'time' => $item->created_at->diffForHumans()
                ];
            });

        $activities = collect($recentStockIns)
            ->concat($recentStockOuts)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalTransactions',
            'totalRevenue',
            'lowStockCount',
            'lowStockProducts',
            'recentTransactions',
            'salesTrend',
            'dates',
            'categoryNames',
            'categoryProductCounts',
            'activities'
        ));
    }
}
