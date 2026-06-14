<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Konstanta tipe stok
     */
    public const STOCK_IN  = 'in';
    public const STOCK_OUT = 'out';

    /**
     * Mass assignment
     */
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'supplier_id',
        'description',
        'purchase_price',
        'selling_price',
        'unit',
        'minimum_stock',
        'is_active',
    ];

    /**
     * Cast attribute
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'minimum_stock'  => 'integer',
        'is_active'      => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Hitung stok produk di gudang tertentu
     */
    public function getStockInWarehouse(int $warehouseId): float
    {
        return (float) ($this->stockMovements()
            ->where('warehouse_id', $warehouseId)
            ->selectRaw("
                COALESCE(SUM(
                    CASE 
                        WHEN type = ? THEN quantity
                        WHEN type = ? THEN -quantity
                        ELSE 0
                    END
                ), 0) as stock
            ", [self::STOCK_IN, self::STOCK_OUT])
            ->value('stock') ?? 0);
    }

    /**
     * Hitung total stok di semua gudang
     */
    public function getTotalStock(): float
    {
        return (float) ($this->stockMovements()
            ->selectRaw("
                COALESCE(SUM(
                    CASE 
                        WHEN type = ? THEN quantity
                        WHEN type = ? THEN -quantity
                        ELSE 0
                    END
                ), 0) as stock
            ", [self::STOCK_IN, self::STOCK_OUT])
            ->value('stock') ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * $product->total_stock
     */
    public function getTotalStockAttribute(): float
    {
        return $this->getTotalStock();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Produk dengan stok di bawah minimum
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw("
            (
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN type = ? THEN quantity
                        WHEN type = ? THEN -quantity
                        ELSE 0
                    END
                ), 0)
                FROM stock_movements
                WHERE stock_movements.product_id = products.id
            ) <= minimum_stock
        ", [self::STOCK_IN, self::STOCK_OUT]);
    }
}
