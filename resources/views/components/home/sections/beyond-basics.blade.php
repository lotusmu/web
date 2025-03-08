<section class="relative isolate">
    <x-home.wrapper>
        <div class="mb-12 space-y-8">
            <x-home.header-icon name="square-3-stack-3d"/>

            <x-home.kicker>
                {{ __('Beyond Basics') }}
            </x-home.kicker>

            <x-home.heading>
                {{ __('Depth beyond the basics.') }}
            </x-home.heading>

            <x-home.text>
                {{ __("Each feature in Lotus Mu is crafted with purpose and depth. These aren't just additions - they're game-changing mechanics that create a richer, more rewarding journey through the realm.") }}
            </x-home.text>

            <x-home.link href="https://wiki.lotusmu.org" external>
                {{ __('Learn more') }}
            </x-home.link>
        </div>

        <flux:tab.group class="mt-10">
            <flux:tabs variant="pills" class="flex overflow-auto">
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
                    {{ __('Events') }}
                </flux:tab>

                <flux:tab name="pets" :accent="false" icon="paw-print">
                    {{ __('Pet Collection') }}
                </flux:tab>
            </flux:tabs>

            <div class="absolute w-full overflow-visible left-0 h-[30rem] mt-8 xl:mt-16 -z-10">
                <x-home.grid-pattern position="top_left"/>
            </div>

            <flux:tab.panel name="wiki">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Wiki') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Ladies and gentlemen - the most comprehensive MU knowledge base in the world -') }}

                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org"
                                           external>
                                    {{ __('the Lotus Mu Wiki.') }}
                                </flux:link>
                            </flux:text>

                            <flux:text>
                                {{ __('What truly sets Lotus Mu apart is our dedication to knowledge sharing and documentation.') }}
                            </flux:text>

                            <flux:text>
                                {{ __('Find everything you need in seconds through our modern interface and powerful search engine.') }}
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="videos/wiki"
                            poster="{{ asset('videos/posters/wiki.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="items">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Evolving Items') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Think Dark Knight should be more powerful than the Leather set?') }}
                            </flux:text>

                            <flux:text class="font-bold">
                                {{ __('We think so too!') }}
                            </flux:text>

                            <flux:text>
                                {{ __('In modern MU Online, having the same excellent options across all items doesn\'t make sense - that\'s why our excellent options scale with item tiers - stronger items, better bonuses.') }}
                            </flux:text>

                            <flux:text>{{ __('Want to see how it works?') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/items/exc-items"
                                           external>
                                    {{ __('Check out the details!') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/items') }}"
                            poster="{{ asset('videos/posters/items.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="quests">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Progressive Quests') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Ever wondered if quests could actually help you progress?') }}
                            </flux:text>

                            <flux:text class="font-bold">
                                {{ __('In Lotus Mu, they absolutely do!') }}
                            </flux:text>

                            <flux:text>
                                {{ __('From hunting spiders to challenging mighty monsters, our quest system grows with you. Each completed quest rewards you with valuable items and points - making your character stronger every step of the way.') }}
                            </flux:text>

                            <flux:text>{{ __('Ready to start your journey?') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/gameplay-systems/quest-system"
                                           external>
                                    {{ __('See the full quest line!') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/quests') }}"
                            poster="{{ asset('images/posters/quests') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="crafting">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Crafting System') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Think crafting in MU Online needs a fresh take?') }}
                            </flux:text>

                            <flux:text class="font-bold">
                                {{ __('We redesigned it completely!') }}
                            </flux:text>

                            <flux:text>
                                {{ __('From unique pet crafting to reimagined classic recipes, our system aligns perfectly with modern MU gameplay - multiple resets, active farming, and exciting rewards.') }}
                            </flux:text>

                            <flux:text>{{ __('Curious about crafting?') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/category/crafting"
                                           external>
                                    {{ __('See what\'s cooking!') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/crafting') }}"
                            poster="{{ asset('videos/posters/crafting.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="events">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Events') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('From intense 1v1 battles in the Hall of Fame to team clashes and PvE challenges - our events are designed for every playstyle.') }}
                            </flux:text>

                            <flux:text>
                                {{ __('Top performers in our weekly rankings receive special rewards for their dedication.') }}
                            </flux:text>

                            <flux:text>{{ __('Want the full schedule?') }}
                                <flux:link variant="ghost"
                                           :href="route('schedule')"
                                           wire:navigate.hover>
                                    {{ __('Check event times') }}
                                </flux:link>
                                {{ __('or') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/category/events"
                                           external>
                                    {{ __('read our guides.') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/events') }}"
                            poster="{{ asset('videos/posters/events.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="pets">
                <x-home.feature-split type="video">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Pet Collection') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Looking for a powerful companion?') }}
                            </flux:text>

                            <flux:text class="font-bold">
                                {{ __('Our pets are game-changers!') }}
                            </flux:text>

                            <flux:text>
                                {{ __('From the mighty Warhorse to the fearsome Ice Dragon, each class has its own unique set of companions. With three power tiers and class-specific bonuses, these pets aren\'t just for show.') }}
                            </flux:text>

                            <flux:text>{{ __('Curious about their powers?') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/items/pets"
                                           external>
                                    {{ __('Meet them!') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-browser-frame
                            video="{{ asset('videos/pets') }}"
                            poster="{{ asset('videos/posters/pets.jpg') }}"
                        />
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>
        </flux:tab.group>
    </x-home.wrapper>
</section>
