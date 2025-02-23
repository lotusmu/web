@props([
    'text',
    'media',
    'type'
])

@if($type == 'video')
    <div class="lg:grid lg:grid-cols-12 lg:gap-8 pt-4 xl:pt-0 mt-4 xl:mt-0">
        <div class="lg:col-span-4 lg:mt-10 xl:mt-18">
            {{ $text }}
        </div>

        <div class="lg:col-span-8">
            {{ $media }}
        </div>
    </div>
@endif

@if($type == 'image')
    <div class="lg:grid lg:grid-cols-12 lg:gap-8 max-lg:space-y-8">
        <div class="lg:col-span-4 space-y-4">
            {{ $text }}
        </div>

        <div class="lg:col-span-8">
            {{ $media }}
        </div>
    </div>
@endif
