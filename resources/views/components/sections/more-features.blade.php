<section class="relative isolate">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12 space-y-6">
            <x-sections.header-icon name="film"/>

            <x-sections.kicker :text="__('More Features')"/>

            <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
                Ancient powers reimagined for modern gameplay.
            </flux:heading>

            <flux:text size="lg" class="mx-auto max-w-2xl leading-8">
                Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                Every feature is carefully crafted to enhance gameplay without compromising the core experience
                that made the game legendary.
            </flux:text>

            <x-sections.link
                href="https://wiki.lotusmu.org" external
                text="{{ __('Learn more') }}"
            />
        </div>

        <flux:tab.group class="mt-10">
            <flux:tabs variant="pills" class="flex overflow-auto sm:mx-0 sm:justify-center">
                <flux:tab name="tab10" :accent="false" icon="swords">Evolving Items</flux:tab>
                <flux:tab name="tab20" :accent="false" icon="scroll">Progressive Quests</flux:tab>
                <flux:tab name="tab30" :accent="false" icon="sparkles">Crafting System</flux:tab>
            </flux:tabs>

            <div class="absolute w-full overflow-visible left-0 h-[30rem] mt-8 xl:mt-16">
                <x-sections.grid-pattern position="top_left"/>
            </div>

            <flux:tab.panel name="tab10">
                <x-feature-split>
                    <x-slot:text>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/mu.mp4') }}"
                            poster="{{ asset('images/auth/image.jpg') }}"
                        />
                    </x-slot:media>
                </x-feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="tab20">
                <x-feature-split>
                    <x-slot:text>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit... Lorem ipsum dolor sit amet,
                        consectetur adipisicing elit. Ipsam, minima?
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/example.mp4') }}"
                            poster="{{ asset('images/auth/image.jpg') }}"
                        />
                    </x-slot:media>
                </x-feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="tab30">
                <x-feature-split>
                    <x-slot:text>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit... Lorem ipsum dolor sit amet,
                        consectetur adipisicing elit. Ipsam, minima? Lorem ipsum dolor sit amet, consectetur
                        adipisicing elit. Consectetur, minus.
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/auth.mp4') }}"
                            poster="{{ asset('images/auth/image.jpg') }}"
                        />
                    </x-slot:media>
                </x-feature-split>
            </flux:tab.panel>
        </flux:tab.group>
    </div>
</section>
