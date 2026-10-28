<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'supplier_id',
        'sku',
        'name',
        'price',
        'stock',
        'min_stock',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Regla única de stock bajo (RF08): stock <= stock mínimo.
     * La misma condición se usa en las consultas con whereColumn('stock', '<=', 'min_stock').
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
