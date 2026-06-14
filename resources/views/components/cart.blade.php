@props([
    'class' => '',
])

<div {{ $attributes->merge([
    'class' => "bg-white rounded-lg shadow-sm border border-gray-200 $class"
]) }}>
    
    @isset($header)
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-800">
            {{ $header }}
        </div>
    @endisset

    <div class="p-6 text-gray-700">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $footer }}
        </div>
    @endisset
</div>
