<section class="relative isolate mt-32 px-6 pt-32 sm:mt-56 sm:pt-40 lg:px-8">
    <x-home.grid-pattern position="top_right"/>

    {{-- Dark theme beams --}}
    <div
        class="absolute inset-x-0 top-10 -z-10 hidden dark:flex transform-gpu justify-center overflow-hidden blur-3xl"
        aria-hidden="true">
        <div
            class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#80caff] via-[#4f46e5] to-[#ec4899] opacity-20"
            style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"
            loading="lazy"
            decoding="async">
        </div>
    </div>

    {{-- Light theme beams --}}
    <div
        class="absolute inset-x-0 top-10 -z-10 dark:hidden flex transform-gpu justify-center overflow-hidden blur-3xl"
        aria-hidden="true">
        <div
            class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#2563eb] via-[#4f46e5] to-[#e11d48] opacity-30"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
            loading="lazy"
            decoding="async">
        </div>
    </div>

    <!-- Content -->
    <div class="mx-auto max-w-3xl">
        <!-- Header Section -->
        <div class="text-center mb-12 space-y-8">
            <x-home.header-icon name="squares-plus"/>

            <x-home.kicker>
                {{ __('Features') }}
            </x-home.kicker>

            <x-home.heading>
                {{ __('Ancient powers reimagined for modern gameplay.') }}
            </x-home.heading>

            <x-home.text class="mx-auto max-w-2xl">
                {{ __('Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                Every feature is carefully crafted to enhance gameplay without compromising the core experience
                that made the game legendary.') }}
            </x-home.text>

            <x-home.link href="https://wiki.lotusmu.org" external>
                {{ __('Learn more') }}
            </x-home.link>
        </div>

        <flux:tab.group>
            <flux:tabs variant="pills" class="flex overflow-auto sm:mx-0 sm:justify-center">
                <flux:tab name="monsters" :accent="false" icon="skull">
                    {{ __('Special Monsters') }}
                </flux:tab>

                <flux:tab name="helper" :accent="false" icon="bot">
                    {{ __('Helper') }}
                </flux:tab>

                <flux:tab name="farm" :accent="false" icon="mountain">
                    {{ __('Farm Areas') }}
                </flux:tab>

                <flux:tab name="rewards" :accent="false" icon="gift">
                    {{ __('Daily Rewards') }}
                </flux:tab>

                <flux:tab name="locations" :accent="false" icon="map-pinned">
                    {{ __('Key Locations') }}
                </flux:tab>
            </flux:tabs>

            <flux:tab.panel name="monsters">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visual Demo -->
                    <div class="bg-white/5 rounded-lg p-6 space-y-4">
                        <div class="w-80 h-8 bg-white/10 rounded"></div>
                        <div class="w-72 h-8 bg-white/10 rounded"></div>
                        <div class="w-64 h-8 bg-white/10 rounded"></div>
                        <div class="w-56 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 h-8 bg-white/10 rounded"></div>
                    </div>

                    <!-- Code Preview -->
                    <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                        Tab 1
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="helper">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visual Demo -->
                    <div class="bg-white/5 rounded-lg p-6 space-y-4">
                        <div class="w-80 h-8 bg-white/10 rounded"></div>
                        <div class="w-72 h-8 bg-white/10 rounded"></div>
                        <div class="w-64 h-8 bg-white/10 rounded"></div>
                        <div class="w-56 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 h-8 bg-white/10 rounded"></div>
                    </div>

                    <!-- Code Preview -->
                    <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                        Tab 2
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="farm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visual Demo -->
                    <div class="bg-white/5 rounded-lg p-6 space-y-4">
                        <div class="w-80 h-8 bg-white/10 rounded"></div>
                        <div class="w-72 h-8 bg-white/10 rounded"></div>
                        <div class="w-64 h-8 bg-white/10 rounded"></div>
                        <div class="w-56 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 h-8 bg-white/10 rounded"></div>
                    </div>

                    <!-- Code Preview -->
                    <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                        Tab 3
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="rewards">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visual Demo -->
                    <div class="bg-white/5 rounded-lg p-6 space-y-4">
                        <div class="w-80 h-8 bg-white/10 rounded"></div>
                        <div class="w-72 h-8 bg-white/10 rounded"></div>
                        <div class="w-64 h-8 bg-white/10 rounded"></div>
                        <div class="w-56 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 h-8 bg-white/10 rounded"></div>
                    </div>

                    <!-- Code Preview -->
                    <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                        Tab 3
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="locations">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visual Demo -->
                    <div class="bg-white/5 rounded-lg p-6 space-y-4">
                        <div class="w-80 h-8 bg-white/10 rounded"></div>
                        <div class="w-72 h-8 bg-white/10 rounded"></div>
                        <div class="w-64 h-8 bg-white/10 rounded"></div>
                        <div class="w-56 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 h-8 bg-white/10 rounded"></div>
                    </div>

                    <!-- Code Preview -->
                    <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                        Tab 3
                    </flux:card>
                </div>
            </flux:tab.panel>
        </flux:tab.group>
    </div>
</section>
