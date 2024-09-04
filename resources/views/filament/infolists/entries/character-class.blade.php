@php
    use App\Enums\Game\CharacterClass;
    $state = $getState();
    $imageSize = $getImageSize();
@endphp

<div class="flex flex-col items-start gap-2">
    @if ($state instanceof CharacterClass)
        <div class="flex items-center flex-col gap-2">
            <img src="{{ asset($state->getImagePath()) }}"
                 alt="{{ $state->getLabel() }}"
                 class="rounded-full object-cover"
                 style="width: {{ $imageSize }}px; height: {{ $imageSize }}px;">
        </div>
    @else
        <span class="text-sm">{{ $state->getLabel() }}</span>
    @endif
</div>
