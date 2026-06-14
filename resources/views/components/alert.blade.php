@props([
    'type' => 'info',
])

@php
$styles = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error'   => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info'    => 'bg-blue-50 border-blue-200 text-blue-800',
];

$icons = [
    'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z"/>',
    'error'   => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.7 7.3a1 1 0 00-1.4 1.4L8.6 10l-1.3 1.3a1 1 0 101.4 1.4L10 11.4l1.3 1.3a1 1 0 001.4-1.4L11.4 10l1.3-1.3a1 1 0 00-1.4-1.4L10 8.6 8.7 7.3z"/>',
    'warning' => '<path fill-rule="evenodd" d="M8.3 3.1c.8-1.4 2.7-1.4 3.5 0l5.6 9.9c.7 1.3-.2 3-1.7 3H4.4c-1.5 0-2.5-1.7-1.7-3l5.6-9.9zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>',
    'info'    => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM11 14a1 1 0 10-2 0v-3a1 1 0 102 0v3zm-1-8a1 1 0 100 2 1 1 0 000-2z"/>',
];

$style = $styles[$type] ?? $styles['info'];
$icon  = $icons[$type] ?? $icons['info'];
@endphp

<div role="alert" {{ $attributes->merge([
    'class' => "border rounded-lg p-4 $style"
]) }}>
    <div class="flex items-start">
        <svg class="h-5 w-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            {!! $icon !!}
        </svg>
        <div class="text-sm">
            {{ $slot }}
        </div>
    </div>
</div>
