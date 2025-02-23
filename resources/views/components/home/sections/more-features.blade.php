<section class="relative isolate">
    <x-home.wrapper>
        <div class="text-center mb-12 space-y-8">
            <x-home.header-icon name="film"/>

            <x-home.kicker>
                {{ __('More Features') }}
            </x-home.kicker>

            <x-home.heading>
                {{ __('Ancient powers reimagined for modern gameplay.') }}
            </x-home.heading>

            <x-home.text>
                {{ __('Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                Every feature is carefully crafted to enhance gameplay without compromising the core experience
                that made the game legendary.') }}
            </x-home.text>

            <x-home.link href="https://wiki.lotusmu.org" external>
                {{ __('Learn more') }}
            </x-home.link>
        </div>

        <flux:tab.group class="mt-10">
            <flux:tabs variant="pills" class="flex overflow-auto lg:justify-center">
                <flux:tab name="wiki" :accent="false" icon="book-open">
                    {{ __('Wiki') }}
                </flux:tab>

                <flux:tab name="items" :accent="false" icon="swords">
                    {{ __('Evolving Items') }}
                </flux:tab>

                <flux:tab name="quests" :accent="false" icon="scroll">
                    {{ __('Progressive Quests') }}
                </flux:tab>

                <flux:tab name="crafting" :accent="false" icon="sparkles">
                    {{ __('Crafting System') }}
                </flux:tab>

                <flux:tab name="events" :accent="false" icon="award">
                    {{ __('Elite Events') }}
                </flux:tab>

                <flux:tab name="pets" :accent="false" icon="paw-print">
                    {{ __('Pet Collection') }}
                </flux:tab>
            </flux:tabs>

            <div class="absolute w-full overflow-visible left-0 h-[30rem] mt-8 xl:mt-16">
                <x-home.grid-pattern position="top_left"/>
            </div>

            <flux:tab.panel name="wiki">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/mu.mp4') }}"
                            poster="{{ asset('images/auth/image.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="items">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/mu.mp4') }}"
                            poster="{{ asset('images/auth/image.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="quests">
                <x-home.feature-split type="video">
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
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="crafting">
                <x-home.feature-split type="video">
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
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="events">
                <x-home.feature-split type="video">
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
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="pets">
                <x-home.feature-split type="video">
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
                </x-home.feature-split>
            </flux:tab.panel>
        </flux:tab.group>
    </x-home.wrapper>
</section>
