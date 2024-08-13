@php
    $state = $getState();
    $imageSize = $getExtraAttributes()['imageSize'] ?? 40;
@endphp

<div class="flex items-center gap-2 px-3 py-4 text-sm">
    <img src="{{ $state ? asset($state->getImagePath()) : '' }}"
         alt="{{ $state ? $state->getLabel() : '' }}"
         @class([
             'rounded-full object-cover',
         ])
         style="width: {{ $imageSize }}px; height: {{ $imageSize }}px;">
    <span>{{ $state ? $state->getLabel() : '' }}</span>
</div>
