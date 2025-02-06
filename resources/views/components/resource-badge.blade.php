@php
    use App\Enums\Utility\ResourceType;
    use Illuminate\Support\Number;
@endphp

@props([
    'value' => null,
    'resource' => null,
])

<flux:badge variant="pill" color="{{ $resource->getBadgeColor() }}" size="sm" {{ $attributes }}>
    @if($resource === ResourceType::ZEN)
        {{  Number::abbreviate($value, precision: 2) }} {{ $resource->getLabel() }}
    @else
        {{ number_format($value) }} {{ $resource->getLabel() }}
    @endif
</flux:badge>
