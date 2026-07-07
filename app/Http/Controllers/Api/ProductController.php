<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Product List with Filters
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Price
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Categories with Product Count
    public function categories()
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    // Dashboard Statistics
    public function dashboard()
    {
        return response()->json([
            'success' => true,

            'total_products' => Product::count(),

            'total_categories' => Category::count(),

            'average_price' => round(Product::avg('price'), 2),

            'highest_price' => Product::max('price'),

            'lowest_price' => Product::min('price')
        ]);
    }

    // Latest Products
    public function latestProducts()
    {
        $products = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Search Suggestions
    public function suggestions(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->search . '%')
            ->limit(5)
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}