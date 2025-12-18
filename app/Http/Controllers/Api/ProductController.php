<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category')
                ->when($request->search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->when($request->category_id, function ($query, $categoryId) {
                    return $query->where('category_id', $categoryId);
                })
                ->when($request->min_price, function ($query, $minPrice) {
                    return $query->where('price', '>=', $minPrice);
                })
                ->when($request->max_price, function ($query, $maxPrice) {
                    return $query->where('price', '<=', $maxPrice);
                });

            // Handle sorting
            if ($request->sort_by) {
                $direction = $request->sort_order === 'desc' ? 'desc' : 'asc';
                $query->orderBy($request->sort_by, $direction);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $products = $query->paginate(12);

            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Products fetched successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function categories()
    {
        try {
            $categories = Category::all();
            
            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories fetched successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories: ' . $e->getMessage()
            ], 500);
        }
    }
}