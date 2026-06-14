<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(20);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::active()->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);

        // ✅ STOK PER GUDANG (BENAR & AMAN)
        $stockPerWarehouse = $product->stockMovements()
            ->select(
                'warehouse_id',
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
            ->with('warehouse')
            ->groupBy('warehouse_id')
            ->having('total_stock', '>', 0)
            ->get();

        return view('products.show', compact('product', 'stockPerWarehouse'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers  = Supplier::active()->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        if ($product->getTotalStock() > 0) {
            return back()->with(
                'error',
                'Tidak bisa menghapus produk yang masih memiliki stok'
            );
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
