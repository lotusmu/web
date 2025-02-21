@php
    $items = [
        ['label' => 'Tokens', 'value' => $tokens, 'color' => 'text-primary-600'],
        ['label' => 'Credits', 'value' => $credits, 'color' => 'text-warning-600'],
        ['label' => 'Zen', 'value' => $zen, 'color' => 'text-success-600'],
    ];
@endphp

<div class="grid grid-cols-3 gap-3 mt-2">
    @foreach ($items as $item)
        <div class="text-center">
            <div class="text-sm font-medium {{ $item['color'] }}">
                {{ $item['label'] }}
            </div>
            <div class="text-lg font-semibold">
                {{ $item['value'] }}
            </div>
        </div>
    @endforeach
</div> 