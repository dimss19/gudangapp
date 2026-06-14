<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        // Total gudang aktif
        $totalWarehouses = Warehouse::active()->count();

        // Total produk aktif
        $totalProducts = Product::active()->count();

        // Produk dengan stok rendah
        $lowStockCount = $this->stockService
            ->getLowStockProducts()
            ->count();

        // Total nilai stok (AMAN)
        $totalStockValue = $this->calculateTotalStockValue();

        // Mutasi stok terbaru
        $recentMovements = StockMovement::with(['product', 'warehouse', 'user'])
            ->latest('movement_date')
            ->limit(10)
            ->get();

        // Stok per gudang (AMAN)
        $stockPerWarehouse = DB::table('stock_movements')
            ->select(
                'warehouse_id',
                DB::raw('COUNT(DISTINCT product_id) as product_count'),
                DB::raw("
                    SUM(
                        CASE
                            WHEN type = '" . Product::STOCK_IN . "' THEN quantity
                            WHEN type = '" . Product::STOCK_OUT . "' THEN -quantity
                            ELSE 0
                        END
                    ) as total_quantity
                ")
            )
            ->groupBy('warehouse_id')
            ->having('total_quantity', '>', 0)
            ->get();

        $warehouses = Warehouse::active()->get()->keyBy('id');

        return view('dashboard', compact(
            'totalWarehouses',
            'totalProducts',
            'lowStockCount',
            'totalStockValue',
            'recentMovements',
            'stockPerWarehouse',
            'warehouses'
        ));
    }

    /**
     * Hitung total nilai stok berdasarkan harga beli (AMAN & EFISIEN)
     */
    private function calculateTotalStockValue(): float
    {
        $stockValues = StockMovement::select(
            'product_id',
            DB::raw("
                SUM(
                    CASE
                        WHEN type = '" . Product::STOCK_IN . "' THEN quantity
                        WHEN type = '" . Product::STOCK_OUT . "' THEN -quantity
                        ELSE 0
                    END
                ) as total_quantity
            ")
        )
        ->groupBy('product_id')
        ->having('total_quantity', '>', 0)
        ->with('product:id,purchase_price')
        ->get();

        return $stockValues->sum(function ($item) {
            return $item->total_quantity * $item->product->purchase_price;
        });
    }
}
