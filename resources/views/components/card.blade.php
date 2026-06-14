<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200']) }}>
    @isset($header)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            {{ $header }}
        </div>
    @endisset

    <div class="px-6 py-4">
        {{ $slot }}
    </div>
</div>



