@props([
    'type' => 'button',
    'variant' => 'primary',
    'disabled' => false,
])

@php
$variants = [
    'primary'   => 'bg-indigo-600 hover:bg-indigo-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success'   => 'bg-green-600 hover:bg-green-700 text-white',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white',
    'warning'   => 'bg-yellow-500 hover:bg-yellow-600 text-white',
    'outline'   => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
];

$baseClass = 'inline-flex items-center justify-center px-4 py-2 rounded-lg font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClass = $variants[$variant] ?? $variants['primary'];

$disabledClass = $disabled
    ? 'opacity-50 cursor-not-allowed pointer-events-none'
    : 'focus:ring-indigo-500';
@endphp

<button
    type="{{ $type }}"
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => "$baseClass $variantClass $disabledClass"
    ]) }}
>
    {{ $slot }}
</button>
