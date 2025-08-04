<flux:main container>
    <x-page-header
        :title="__('Live Streaming Now')"
        :kicker="__('Live')"
        :description="__('Watch our content creators live and discover amazing gameplay moments.')"
    />

    <div
        x-data="streamsPage(@js($streams), @js($selectedStreamId), '{{ $viewMode }}')"
        x-init="init()"
    >
        <x-streams.header
            :streams="$streams"
            :total-viewers="$this->totalViewers"
            :view-mode="$viewMode"
        />

        @if(empty($streams))
            <x-streams.empty-state/>
        @else
            <!-- Featured View -->
            <div x-show="viewMode === 'featured'" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <x-streams.featured-player :streams="$streams"/>
                    <x-streams.stream-selector :streams="$streams"/>
                </div>
            </div>

            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($streams as $stream)
                        <x-streams.stream-card :stream="$stream"/>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</flux:main>