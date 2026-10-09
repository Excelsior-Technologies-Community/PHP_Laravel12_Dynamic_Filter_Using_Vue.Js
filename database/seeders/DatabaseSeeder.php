<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categoriesData = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing & Fashion', 'slug' => 'clothing-fashion'],
            ['name' => 'Books & Media', 'slug' => 'books-media'],
            ['name' => 'Home & Living', 'slug' => 'home-living'],
            ['name' => 'Sports & Fitness', 'slug' => 'sports-fitness'],
            ['name' => 'Beauty & Personal Care', 'slug' => 'beauty-personal-care']
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['name']] = Category::firstOrCreate(['slug' => $c['slug']], ['name' => $c['name']]);
        }

        $sampleProducts = [
            // Electronics
            ['name' => 'MacBook Pro 16 M3 Max', 'brand' => 'Apple', 'price' => 2499.00, 'category' => 'Electronics', 'rating' => 4.9, 'in_stock' => true, 'qty' => 15],
            ['name' => 'iPhone 15 Pro Max', 'brand' => 'Apple', 'price' => 1199.99, 'category' => 'Electronics', 'rating' => 4.8, 'in_stock' => true, 'qty' => 25],
            ['name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 'price' => 1299.00, 'category' => 'Electronics', 'rating' => 4.7, 'in_stock' => true, 'qty' => 18],
            ['name' => 'Sony WH-1000XM5 Headphones', 'brand' => 'Sony', 'price' => 399.99, 'category' => 'Electronics', 'rating' => 4.8, 'in_stock' => true, 'qty' => 30],
            ['name' => 'Bose QuietComfort Ultra', 'brand' => 'Bose', 'price' => 429.00, 'category' => 'Electronics', 'rating' => 4.6, 'in_stock' => true, 'qty' => 12],
            ['name' => 'Logitech MX Master 3S Mouse', 'brand' => 'Logitech', 'price' => 99.99, 'category' => 'Electronics', 'rating' => 4.9, 'in_stock' => true, 'qty' => 50],
            ['name' => 'Dell XPS 15 OLED Laptop', 'brand' => 'Dell', 'price' => 1899.00, 'category' => 'Electronics', 'rating' => 4.5, 'in_stock' => false, 'qty' => 0],

            // Clothing
            ['name' => 'Nike Tech Fleece Hoodie', 'brand' => 'Nike', 'price' => 130.00, 'category' => 'Clothing & Fashion', 'rating' => 4.6, 'in_stock' => true, 'qty' => 40],
            ['name' => 'Nike Air Force 1 Sneakers', 'brand' => 'Nike', 'price' => 115.00, 'category' => 'Clothing & Fashion', 'rating' => 4.7, 'in_stock' => true, 'qty' => 35],
            ['name' => 'Adidas Ultraboost Light', 'brand' => 'Adidas', 'price' => 190.00, 'category' => 'Clothing & Fashion', 'rating' => 4.5, 'in_stock' => true, 'qty' => 22],
            ['name' => 'Puma Essential Training Jacket', 'brand' => 'Puma', 'price' => 75.00, 'category' => 'Clothing & Fashion', 'rating' => 4.2, 'in_stock' => false, 'qty' => 0],

            // Sports
            ['name' => 'Nike Pro Fitness Training Mat', 'brand' => 'Nike', 'price' => 45.00, 'category' => 'Sports & Fitness', 'rating' => 4.4, 'in_stock' => true, 'qty' => 60],
            ['name' => 'Adidas Performance Dumbbell Set', 'brand' => 'Adidas', 'price' => 85.00, 'category' => 'Sports & Fitness', 'rating' => 4.3, 'in_stock' => true, 'qty' => 14],

            // Home & Living
            ['name' => 'Samsung Smart Air Purifier', 'brand' => 'Samsung', 'price' => 249.99, 'category' => 'Home & Living', 'rating' => 4.6, 'in_stock' => true, 'qty' => 19],
            ['name' => 'Sony Soundbar System 5.1', 'brand' => 'Sony', 'price' => 499.00, 'category' => 'Home & Living', 'rating' => 4.7, 'in_stock' => true, 'qty' => 8],

            // Books
            ['name' => 'Clean Code: Handbook of Agile Software', 'brand' => 'Pearson', 'price' => 42.50, 'category' => 'Books & Media', 'rating' => 4.9, 'in_stock' => true, 'qty' => 100],
            ['name' => 'Designing Data-Intensive Applications', 'brand' => 'OReilly', 'price' => 54.99, 'category' => 'Books & Media', 'rating' => 5.0, 'in_stock' => true, 'qty' => 45]
        ];

        foreach ($sampleProducts as $p) {
            Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'name' => $p['name'],
                    'description' => 'Premium high quality ' . $p['name'] . ' by ' . $p['brand'],
                    'price' => $p['price'],
                    'brand' => $p['brand'],
                    'category_id' => $categories[$p['category']]->id,
                    'rating' => $p['rating'],
                    'in_stock' => $p['in_stock'],
                    'stock_quantity' => $p['qty']
                ]
            );
        }
    }
}