<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(20);

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        Warehouse::create($request->validated());

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Gudang berhasil ditambahkan');
    }

    public function show(Warehouse $warehouse)
    {
        $stockSummary = StockMovement::where('warehouse_id', $warehouse->id)
            ->select(
                'product_id',
                DB::raw("
                    SUM(
                        CASE
                            WHEN type = 'in'  THEN quantity
                            WHEN type = 'out' THEN -quantity
                            ELSE 0
                        END
                    ) as total_stock
                ")
            )
            ->with('product')
            ->groupBy('product_id')
            ->having('total_stock', '>', 0)
            ->get();

        return view('warehouses.show', compact('warehouse', 'stockSummary'));
    }

    public function edit(Warehouse $warehouse)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Gudang berhasil diupdate');
    }

    public function destroy(Warehouse $warehouse)
    {
        $totalStock = StockMovement::where('warehouse_id', $warehouse->id)
            ->selectRaw("
                SUM(
                    CASE
                        WHEN type = 'in'  THEN quantity
                        WHEN type = 'out' THEN -quantity
                        ELSE 0
                    END
                ) as total_stock
            ")
            ->value('total_stock');

        if ($totalStock > 0) {
            return back()->with(
                'error',
                'Tidak bisa menghapus gudang yang masih memiliki stok'
            );
        }

        $warehouse->delete();

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Gudang berhasil dihapus');
    }
}
