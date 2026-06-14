@extends('layouts.app')

@section('title', 'Tambah Gudang')

@section('content')
@if(!auth()->user()->isAdmin())
    <x-alert type="error">
        Anda tidak memiliki akses ke halaman ini.
    </x-alert>
@else
<div class="max-w-2xl mx-auto space-y-6">

    <x-card>
        <x-slot name="header">
            <h2 class="text-xl font-bold text-gray-900">
                Tambah Gudang Baru
            </h2>
        </x-slot>

        <form method="POST" action="{{ route('warehouses.store') }}" class="space-y-6">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Nama Gudang <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                >
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kode --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Kode Gudang <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="WH-001"
                    required
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                >
                @error('code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipe --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Tipe Gudang <span class="text-red-500">*</span>
                </label>
                <select
                    name="type"
                    required
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Pilih Tipe</option>
                    @foreach(\App\Enums\WarehouseTypeEnum::cases() as $type)
                        <option
                            value="{{ $type->value }}"
                            @selected(old('type') === $type->value)
                        >
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Alamat
                </label>
                <textarea
                    name="address"
                    rows="3"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                >{{ old('address') }}</textarea>
            </div>

            {{-- Telepon --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Telepon
                </label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', true))
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
                <span class="text-sm text-gray-700">
                    Gudang Aktif
                </span>
            </div>

            {{-- Action --}}
            <div class="flex justify-end gap-3 pt-4">
                <x-button
                    variant="outline"
                    href="{{ route('warehouses.index') }}"
                >
                    Batal
                </x-button>

                <x-button type="submit">
                    Simpan
                </x-button>
            </div>

        </form>
    </x-card>

</div>
@endif
@endsection
