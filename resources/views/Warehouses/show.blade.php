@extends('layouts.app')

@section('title', 'Detail Gudang')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Detail Gudang</h1>
        <div class="flex gap-3">
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition">
                ✏️ Edit
            </a>
            <a href="{{ route('warehouses.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Gudang -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Gudang</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Gudang</p>
                        <p class="text-base font-semibold text-gray-900">{{ $warehouse->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kode Gudang</p>
                        <p class="text-base font-semibold text-gray-900">{{ $warehouse->code }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tipe Gudang</p>
                        @php
                            $typeColors = ['main' => 'bg-blue-100 text-blue-800', 'branch' => 'bg-green-100 text-green-800', 'return' => 'bg-yellow-100 text-yellow-800'];
                            $typeLabels = ['main' => 'Gudang Utama', 'branch' => 'Gudang Cabang', 'return' => 'Gudang Retur'];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors[$warehouse->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $typeLabels[$warehouse->type] ?? ucfirst($warehouse->type) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                @if($warehouse->address)
                <div>
                    <p class="text-sm text-gray-500">Alamat</p>
                    <p class="text-base text-gray-900">{{ $warehouse->address }}</p>
                </div>
                @endif

                @if($warehouse->phone)
                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="text-base text-gray-900">{{ $warehouse->phone }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistik -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $warehouse->stockMovements->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jenis Produk</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stockSummary->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Produk di Gudang -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Stok Produk di Gudang Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockSummary as $stock)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $stock->product->sku }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $stock->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $stock->product->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">
                                <span class="{{ $stock->total_stock <= $stock->product->minimum_stock ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $stock->total_stock }} {{ $stock->product->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($stock->total_stock <= $stock->product->minimum_stock)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Stok Rendah
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada stok produk di gudang ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection