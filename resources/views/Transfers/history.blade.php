@extends('layouts.app')

@section('title', 'History Transfer')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">History Transfer Stok</h1>
        <a href="{{ route('transfers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
            + Transfer Baru
        </a>
    </div>

    <!-- List Transfer -->
    <div class="space-y-4">
        @forelse($groupedTransfers as $referenceId => $transfers)
            @php
                $transferOut = $transfers->firstWhere('type', 'transfer_out');
                $transferIn = $transfers->firstWhere('type', 'transfer_in');
            @endphp
            
            @if($transferOut && $transferIn)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Header Info -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-indigo-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Transfer ID</p>
                                    <p class="text-sm font-mono text-gray-900">{{ Str::limit($referenceId, 20) }}</p>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $transferOut->movement_date->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">User</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $transferOut->user->name }}</p>
                                </div>
                            </div>

                            <!-- Transfer Flow -->
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                                <!-- From Warehouse -->
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-xs text-red-600 font-medium mb-1">DARI</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $transferOut->warehouse->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($transferOut->warehouse->type) }}</p>
                                </div>

                                <!-- Arrow -->
                                <div class="flex justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>

                                <!-- Product Info -->
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <p class="text-xs text-indigo-600 font-medium mb-1">PRODUK</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $transferOut->product->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $transferOut->product->sku }}</p>
                                    <p class="text-lg font-bold text-indigo-600 mt-2">{{ abs($transferOut->quantity) }} {{ $transferOut->product->unit }}</p>
                                </div>

                                <!-- Arrow -->
                                <div class="flex justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>

                                <!-- To Warehouse -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="text-xs text-green-600 font-medium mb-1">KE</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $transferIn->warehouse->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($transferIn->warehouse->type) }}</p>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($transferOut->notes)
                            <div class="mt-4 bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-medium mb-1">Catatan:</p>
                                <p class="text-sm text-gray-700">{{ $transferOut->notes }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div class="ml-4">
                            <a href="{{ route('transfers.show', $referenceId) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transfer</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat transfer stok baru</p>
                <div class="mt-6">
                    <a href="{{ route('transfers.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                        + Transfer Baru
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection