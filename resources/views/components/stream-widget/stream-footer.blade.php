<div
    class="p-3 bg-gradient-to-r from-purple-600/10 to-blue-600/10 dark:from-purple-600/15 dark:to-blue-600/15">
    {{--                     :href="`https://twitch.tv/${getCurrentStream()?.channel_name}`"--}}
    {{--                     target="_blank"--}}
    <div class="flex space-x-2">

        <flux:button variant="primary" size="sm" class="!bg-purple-600 hover:!bg-purple-700  text-white text-xs  w-full"
                     icon="video-camera">
            All Streams
        </flux:button>
        <flux:button variant="ghost" size="sm" icon="arrow-top-right-on-square"
                     class=" text-xs"
        >
            Watch Full
        </flux:button>
    </div>
</div>
