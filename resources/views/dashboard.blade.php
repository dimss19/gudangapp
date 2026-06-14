<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-lg shadow p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Gudang Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalWarehouses) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Produk Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Stok Rendah</p>
                    <p class="mt-2 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ number_format($lowStockCount) }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Nilai Stok</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="bg-white rounded-lg shadow border border-gray-200 lg:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Mutasi Stok Terbaru</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gudang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentMovements as $movement)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ optional($movement->movement_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $movement->product->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movement->warehouse->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type->isPositive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movement->type->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                            {{ number_format($movement->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                            Belum ada mutasi stok.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Stok per Gudang</h3>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse($stockPerWarehouse as $stock)
                            <div class="px-6 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $warehouses[$stock->warehouse_id]->name ?? 'Gudang tidak aktif' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ number_format($stock->product_count) }} produk
                                        </p>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($stock->total_quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada stok tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
