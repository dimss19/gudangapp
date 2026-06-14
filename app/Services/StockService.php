<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Barang masuk (stok bertambah)
     */
    public function addStock(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {

            return StockMovement::create([
                'product_id'     => $data['product_id'],
                'warehouse_id'   => $data['warehouse_id'],
                'user_id'        => auth()->id(),
                'type'           => Product::STOCK_IN,
                'quantity'       => abs($data['quantity']), // SELALU POSITIF
                'price'          => $data['price'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id'   => $data['reference_id'] ?? null,
                'notes'          => $data['notes'] ?? null,
                'movement_date'  => now(),
            ]);
        });
    }

    /**
     * Barang keluar (stok berkurang)
     */
    public function reduceStock(array $data): StockMovement
    {
        $currentStock = $this->getCurrentStock(
            $data['product_id'],
            $data['warehouse_id']
        );

        if ($currentStock < $data['quantity']) {
            throw new Exception(
                'Stok tidak mencukupi. Stok tersedia: ' . $currentStock
            );
        }

        return DB::transaction(function () use ($data) {

            return StockMovement::create([
                'product_id'     => $data['product_id'],
                'warehouse_id'   => $data['warehouse_id'],
                'user_id'        => auth()->id(),
                'type'           => Product::STOCK_OUT,
                'quantity'       => abs($data['quantity']), // POSITIF
                'price'          => $data['price'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id'   => $data['reference_id'] ?? null,
                'notes'          => $data['notes'] ?? null,
                'movement_date'  => now(),
            ]);
        });
    }

    /**
     * Hitung stok saat ini (AMAN)
     */
    public function getCurrentStock(int $productId, int $warehouseId): float
    {
        return (float) (
            StockMovement::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->selectRaw("
                    COALESCE(SUM(
                        CASE
                            WHEN type = ? THEN quantity
                            WHEN type = ? THEN -quantity
                            ELSE 0
                        END
                    ), 0) as stock
                ", [Product::STOCK_IN, Product::STOCK_OUT])
                ->value('stock') ?? 0
        );
    }

    /**
     * Laporan stok per gudang / semua gudang
     */
    public function getStockReport(int $warehouseId = null)
    {
        $query = StockMovement::select(
            'product_id',
            'warehouse_id',
            DB::raw("
                SUM(
                    CASE
                        WHEN type = '" . Product::STOCK_IN . "' THEN quantity
                        WHEN type = '" . Product::STOCK_OUT . "' THEN -quantity
                        ELSE 0
                    END
                ) as total_stock
            ")
        )
        ->with(['product', 'warehouse'])
        ->groupBy('product_id', 'warehouse_id');

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        return $query
            ->having('total_stock', '>', 0)
            ->get();
    }

    /**
     * Produk dengan stok rendah
     */
    public function getLowStockProducts(int $warehouseId = null)
    {
        return $this->getStockReport($warehouseId)
            ->filter(function ($item) {
                return $item->total_stock <= $item->product->minimum_stock;
            });
    }
}
