<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TransferService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Transfer stok antar gudang (AMAN & KONSISTEN)
     */
    public function transferStock(array $data): array
    {
        $fromWarehouseId = $data['from_warehouse_id'];
        $toWarehouseId   = $data['to_warehouse_id'];
        $productId       = $data['product_id'];
        $quantity        = abs($data['quantity']);

        if ($fromWarehouseId === $toWarehouseId) {
            throw new Exception('Gudang asal dan tujuan tidak boleh sama');
        }

        $currentStock = $this->stockService
            ->getCurrentStock($productId, $fromWarehouseId);

        if ($currentStock < $quantity) {
            throw new Exception(
                "Stok tidak mencukupi. Stok tersedia: {$currentStock}"
            );
        }

        $referenceId = Str::uuid()->toString();

        return DB::transaction(function () use (
            $data,
            $referenceId,
            $quantity,
            $fromWarehouseId,
            $toWarehouseId,
            $productId
        ) {

            // OUT dari gudang asal
            $transferOut = StockMovement::create([
                'product_id'     => $productId,
                'warehouse_id'   => $fromWarehouseId,
                'user_id'        => auth()->id(),
                'type'           => Product::STOCK_OUT,
                'quantity'       => $quantity, // POSITIF
                'reference_type' => 'transfer',
                'reference_id'   => $referenceId,
                'notes'          => $data['notes'] ?? 'Transfer ke gudang tujuan',
                'movement_date'  => now(),
            ]);

            // IN ke gudang tujuan
            $transferIn = StockMovement::create([
                'product_id'     => $productId,
                'warehouse_id'   => $toWarehouseId,
                'user_id'        => auth()->id(),
                'type'           => Product::STOCK_IN,
                'quantity'       => $quantity, // POSITIF
                'reference_type' => 'transfer',
                'reference_id'   => $referenceId,
                'notes'          => $data['notes'] ?? 'Transfer dari gudang asal',
                'movement_date'  => now(),
            ]);

            return [
                'transfer_out' => $transferOut,
                'transfer_in'  => $transferIn,
                'reference_id' => $referenceId,
            ];
        });
    }

    /**
     * History transfer
     */
    public function getTransferHistory(string $referenceId = null)
    {
        $query = StockMovement::where('reference_type', 'transfer')
            ->with(['product', 'warehouse', 'user'])
            ->orderBy('movement_date', 'desc');

        if ($referenceId) {
            $query->where('reference_id', $referenceId);
        }

        return $query->get();
    }
}
