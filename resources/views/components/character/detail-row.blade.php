@props([
    'label',
    'value' => ''
])

<div class="grid grid-cols-3 gap-4">
    <flux:text>{{ $label }}</flux:text>
    <flux:heading class="col-span-2">{{ (string) $value }}</flux:heading>
</div>
