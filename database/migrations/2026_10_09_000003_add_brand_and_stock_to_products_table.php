<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'brand')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('brand', 100)->nullable()->after('category_id');
                $table->boolean('in_stock')->default(true)->after('brand');
                $table->decimal('rating', 3, 2)->default(4.50)->after('in_stock');
                $table->integer('stock_quantity')->default(20)->after('rating');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'brand')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['brand', 'in_stock', 'rating', 'stock_quantity']);
            });
        }
    }
};
