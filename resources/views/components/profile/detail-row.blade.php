@props([
    'label',
    'value' => ''
])

<div class="grid grid-cols-3 gap-4">
    <flux:text>{{ $label }}</flux:text>

    @if(is_string($value) || is_numeric($value))
        <flux:heading class="col-span-2">{{ $value }}</flux:heading>
    @else
        <div class="col-span-2">
            {{ $value }}
        </div>
    @endif
</div>
