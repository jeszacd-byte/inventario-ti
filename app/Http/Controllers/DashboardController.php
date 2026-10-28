<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit(6)
            ->get();

        $byCategory = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockCount',
            'lowStockProducts',
            'byCategory'
        ));
    }
}
