<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports', 'slug' => 'sports'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            
            // Create 8-12 products for each category
            Product::factory(rand(8, 12))->create([
                'category_id' => $category->id,
            ]);
        }

        // Create some extra products
        Product::factory(20)->create();
    }
}