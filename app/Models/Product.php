<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_id',
        'brand',
        'in_stock',
        'rating',
        'stock_quantity'
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'in_stock' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}