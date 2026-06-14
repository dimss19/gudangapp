<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Services\StockService;
use App\Http\Requests\StoreStockMovementRequest;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'warehouse', 'user'])
            ->when($request->warehouse_id, fn ($q, $id) =>
                $q->where('warehouse_id', $id)
            )
            ->when($request->type, fn ($q, $type) =>
                $q->where('type', $type)
            )
            ->when($request->product_id, fn ($q, $id) =>
                $q->where('product_id', $id)
            )
            ->latest('movement_date')
            ->paginate(50);

        $warehouses = Warehouse::active()->get();
        $products   = Product::active()->get();

        return view('stock-movements.index', compact(
            'movements',
            'warehouses',
            'products'
        ));
    }

    public function create()
    {
        $products   = Product::active()->get();
        $warehouses = Warehouse::active()->get();

        return view('stock-movements.create', compact(
            'products',
            'warehouses'
        ));
    }

    public function store(StoreStockMovementRequest $request)
    {
        try {
            $data = $request->validated();

            if ($data['type'] === Product::STOCK_IN) {
                $this->stockService->addStock($data);
                $message = 'Stok berhasil ditambahkan';
            } else {
                $this->stockService->reduceStock($data);
                $message = 'Stok berhasil dikurangi';
            }

            return redirect()
                ->route('stock-movements.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $warehouseId = $request->warehouse_id;

        $stockReport      = $this->stockService->getStockReport($warehouseId);
        $lowStockProducts = $this->stockService->getLowStockProducts($warehouseId);
        $warehouses       = Warehouse::active()->get();

        return view('stock-movements.report', compact(
            'stockReport',
            'lowStockProducts',
            'warehouses'
        ));
    }
}
