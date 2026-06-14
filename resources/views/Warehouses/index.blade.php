@extends('layouts.app')

@section('title', 'Daftar Gudang')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">
            Daftar Gudang
        </h1>

        @if(auth()->user()->isAdmin())
            <x-button
                variant="primary"
                href="{{ route('warehouses.create') }}"
            >
                + Tambah Gudang
            </x-button>
        @endif
    </div>

    {{-- Table --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($warehouses as $warehouse)
                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-sm font-medium">
                                {{ $warehouse->code }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $warehouse->name }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                @php
                                    // Konversi nilai string ke enum agar bisa memanggil method label() dan color()
                                    $type = \App\Enums\WarehouseTypeEnum::from($warehouse->type);
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    bg-{{ $type->color() }}-100
                                    text-{{ $type->color() }}-800">
                                    {{ $type->label() }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ \Illuminate\Support\Str::limit($warehouse->address, 30) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $warehouse->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $warehouse->stock_movements_count }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('warehouses.show', $warehouse) }}"
                                   class="text-indigo-600 hover:underline">
                                    Detail
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('warehouses.edit', $warehouse) }}"
                                       class="text-yellow-600 hover:underline">
                                        Edit
                                    </a>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                                Tidak ada gudang ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Pagination --}}
    <div>
        {{ $warehouses->links() }}
    </div>

</div>
@endsection
