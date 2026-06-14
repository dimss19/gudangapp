@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Detail Produk</h1>
        <div class="flex gap-3">
            <a href="{{ route('products.edit', $product) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition">
                ✏️ Edit
            </a>
            <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Produk -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Produk</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Produk</p>
                        <p class="text-base font-semibold text-gray-900">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">SKU</p>
                        <p class="text-base font-semibold text-gray-900">{{ $product->sku }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="text-base font-semibold text-gray-900">{{ $product->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Supplier</p>
                        <p class="text-base font-semibold text-gray-900">{{ $product->supplier->name }}</p>
                    </div>
                </div>

                @if($product->description)
                <div>
                    <p class="text-sm text-gray-500">Deskripsi</p>
                    <p class="text-base text-gray-900">{{ $product->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Harga Beli</p>
                        <p class="text-base font-semibold text-gray-900">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga Jual</p>
                        <p class="text-base font-semibold text-gray-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Satuan</p>
                        <p class="text-base font-semibold text-gray-900">{{ ucfirst($product->unit) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Stok Minimum</p>
                        <p class="text-base font-semibold text-gray-900">{{ $product->minimum_stock }} {{ $product->unit }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Total Stok</h3>
            </div>
            <div class="p-6">
                @php
                    $totalStock = $product->getTotalStock();
                    $isLow = $totalStock <= $product->minimum_stock;
                @endphp
                <div class="text-center">
                    <p class="text-5xl font-bold {{ $isLow ? 'text-red-600' : 'text-green-600' }}">
                        {{ $totalStock }}
                    </p>
                    <p class="text-gray-500 mt-2">{{ $product->unit }}</p>
                    
                    @if($isLow)
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-800 font-medium">⚠️ Stok Rendah!</p>
                        <p class="text-xs text-red-600 mt-1">Segera lakukan restok</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stok per Gudang -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Stok per Gudang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockPerWarehouse as $stock)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $stock->warehouse->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $typeColors = ['main' => 'bg-blue-100 text-blue-800', 'branch' => 'bg-green-100 text-green-800', 'return' => 'bg-yellow-100 text-yellow-800'];
                                    $typeLabels = ['main' => 'Utama', 'branch' => 'Cabang', 'return' => 'Retur'];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors[$stock->warehouse->type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $typeLabels[$stock->warehouse->type] ?? ucfirst($stock->warehouse->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($stock->warehouse->address, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right {{ $stock->total_stock <= $product->minimum_stock ? 'text-red-600' : 'text-green-600' }}">
                                {{ $stock->total_stock }} {{ $product->unit }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Produk ini belum memiliki stok di gudang manapun
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection