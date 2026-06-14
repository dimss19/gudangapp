@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Stok</h1>
        <a href="{{ route('stock-movements.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
            ← Kembali
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4">
            <select name="warehouse_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Gudang</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Produk dengan Stok Rendah -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-800">Peringatan Stok Rendah</h3>
                <p class="text-sm text-red-700 mt-1">Ada {{ $lowStockProducts->count() }} produk dengan stok di bawah minimum</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Laporan Stok -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Laporan Stok Real-Time</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok Minimum</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nilai (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockReport as $stock)
                        @php
                            $isLow = $stock->total_stock <= $stock->product->minimum_stock;
                            $stockValue = $stock->total_stock * $stock->product->purchase_price;
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $isLow ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $stock->product->sku }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $stock->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $stock->warehouse->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $stock->product->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right {{ $isLow ? 'text-red-600' : 'text-green-600' }}">
                                {{ $stock->total_stock }} {{ $stock->product->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                {{ $stock->product->minimum_stock }} {{ $stock->product->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ number_format($stockValue, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($isLow)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        ⚠️ Stok Rendah
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ✓ Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data stok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($stockReport->count() > 0)
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            Total Nilai Stok:
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            Rp {{ number_format($stockReport->sum(fn($item) => $item->total_stock * $item->product->purchase_price), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Produk</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stockReport->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Produk Stok Rendah</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $lowStockProducts->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Item</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stockReport->sum('total_stock') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Nilai</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">
                Rp {{ number_format($stockReport->sum(fn($item) => $item->total_stock * $item->product->purchase_price), 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>
@endsection