<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Product List with Multi-Faceted Dynamic Filters
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        // Single or Multi-Category Filter
        if ($request->filled('category_ids')) {
            $categoryIds = is_array($request->category_ids) 
                ? $request->category_ids 
                : explode(',', $request->category_ids);
            $query->whereIn('category_id', array_filter($categoryIds));
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Multi-Brand Filter
        if ($request->filled('brands')) {
            $brands = is_array($request->brands) 
                ? $request->brands 
                : explode(',', $request->brands);
            $query->whereIn('brand', array_filter($brands));
        }

        // In-Stock Only Watchdog Filter
        if ($request->boolean('in_stock', false)) {
            $query->where('in_stock', true)->where('stock_quantity', '>', 0);
        }

        // Price Range Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Min Rating Filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', (float) $request->min_rating);
        }

        // Sorting Engine
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = strtolower($request->input('sort_order', 'desc'));

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = (int) $request->input('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'total_filtered' => $products->total(),
                'min_price_bound' => (float) Product::min('price'),
                'max_price_bound' => (float) Product::max('price'),
            ]
        ]);
    }

    /**
     * Categories with Product Counts
     */
    public function categories()
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Brands with Product Counts
     */
    public function brands()
    {
        $brands = Product::select('brand', DB::raw('count(*) as count'))
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->groupBy('brand')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    /**
     * Dashboard Statistics for Real-time Chart
     */
    public function dashboard()
    {
        $categories = Category::withCount('products')
            ->withSum('products as total_asset_value', 'price')
            ->get();

        return response()->json([
            'success' => true,
            'total_products' => Product::count(),
            'in_stock_count' => Product::where('in_stock', true)->count(),
            'total_categories' => Category::count(),
            'total_brands' => Product::distinct('brand')->count('brand'),
            'average_price' => round(Product::avg('price') ?? 0, 2),
            'highest_price' => (float) (Product::max('price') ?? 0),
            'lowest_price' => (float) (Product::min('price') ?? 0),
            'total_inventory_value' => round(Product::sum('price') ?? 0, 2),
            'categories' => $categories
        ]);
    }

    /**
     * Latest Products
     */
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

    /**
     * Live Search Auto-Suggestions
     */
    public function suggestions(Request $request)
    {
        $search = $request->input('search', '');
        if (strlen($search) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $products = Product::where('name', 'like', '%' . $search . '%')
            ->orWhere('brand', 'like', '%' . $search . '%')
            ->limit(6)
            ->get(['id', 'name', 'brand', 'price']);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}