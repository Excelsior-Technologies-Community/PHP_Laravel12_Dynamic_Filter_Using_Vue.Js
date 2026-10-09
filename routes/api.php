<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/brands', [ProductController::class, 'brands']);
Route::get('/dashboard', [ProductController::class, 'dashboard']);
Route::get('/latest-products', [ProductController::class, 'latestProducts']);
Route::get('/suggestions', [ProductController::class, 'suggestions']);