<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Se agregan las columnas permitiendo nulo para no fallar con productos existentes.
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 50)->nullable()->unique()->after('supplier_id');
            $table->unsignedInteger('min_stock')->default(5)->after('stock');
        });

        // 2. Los productos que ya existían reciben un SKU provisional único.
        DB::table('products')->whereNull('sku')->orderBy('id')->each(function ($product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['sku' => 'SKU-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT)]);
        });

        // 3. El SKU pasa a ser obligatorio.
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn(['sku', 'min_stock']);
        });
    }
};
