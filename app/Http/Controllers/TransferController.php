<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\TransferService;
use App\Http\Requests\TransferStockRequest;

class TransferController extends Controller
{
    protected TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function create()
    {
        $warehouses = Warehouse::active()->get();
        $products   = Product::active()->get();

        return view('transfers.create', compact('warehouses', 'products'));
    }

    public function store(TransferStockRequest $request)
    {
        try {
            $result = $this->transferService
                ->transferStock($request->validated());

            return redirect()
                ->route('transfers.history')
                ->with(
                    'success',
                    'Transfer stok berhasil. Ref: ' . $result['reference_id']
                );

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $transfers = $this->transferService->getTransferHistory();

        $groupedTransfers = $transfers->groupBy('reference_id');

        return view('transfers.history', compact('groupedTransfers'));
    }

    public function show(string $referenceId)
    {
        $transfers = $this->transferService->getTransferHistory($referenceId);

        return view('transfers.show', compact('transfers'));
    }
}
