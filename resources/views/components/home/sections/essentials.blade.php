<section class="relative isolate mt-32 pt-32">
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
    <x-home.wrapper>
        <!-- Header Section -->
        <div class="mb-12 space-y-8">
            <x-home.header-icon name="atom"/>

            <x-home.kicker>
                {{ __('Essentials') }}
            </x-home.kicker>

            <x-home.heading>
                {{ __('Ancient powers reimagined for modern gameplay.') }}
            </x-home.heading>

            <x-home.text>
                {{ __('Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula. Every feature is carefully crafted to enhance gameplay without compromising the core experience that made the game legendary.') }}
            </x-home.text>

            <x-home.link href="https://wiki.lotusmu.org" external>
                {{ __('Master the Basics') }}
            </x-home.link>
        </div>

        <flux:tab.group>
            <flux:tabs variant="pills" class="flex overflow-auto">
                <flux:tab name="farm" :accent="false" icon="mountain">
                    {{ __('Farm Areas') }}
                </flux:tab>

                <flux:tab name="monsters" :accent="false" icon="skull">
                    {{ __('Special Monsters') }}
                </flux:tab>

                <flux:tab name="rewards" :accent="false" icon="gift">
                    {{ __('Daily Rewards') }}
                </flux:tab>

                <flux:tab name="helper" :accent="false" icon="bot">
                    {{ __('Helper') }}
                </flux:tab>
            </flux:tabs>

            <flux:tab.panel name="farm">
                <x-home.feature-split type="image">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Farm Areas') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('We have two designated farming maps:') }}
                            </flux:text>

                            <flux:text>
                                <ul class="list-disc ml-8">
                                    <li>
                                        <flux:link variant="ghost"
                                                   href="https://wiki.lotusmu.org/maps/icewrack"
                                                   external>
                                            {{ __('Icewrack') }}
                                        </flux:link>
                                    </li>
                                    <li>
                                        <flux:link variant="ghost"
                                                   href="https://wiki.lotusmu.org/maps/ashkavor"
                                                   external>
                                            {{ __('Ashkavor') }}
                                        </flux:link>
                                    </li>
                                </ul>
                            </flux:text>

                            <flux:text>
                                {{  __('These areas are home to special monsters with valuable loot. Visit them regularly to make the most of your farming runs.') }}
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-home.feature-picture src="images/essentials/farm-areas"
                                                alt="Farm areas in Lotus Mu including Ashkavor and Icewrack"/>
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="monsters">
                <x-home.feature-split type="image">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Special Monsters') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Plenty of special monsters scattered throughout the realm - from weak to strong, each rewarding in its own way. Find them roaming our carefully crafted farm areas, or face the most formidable ones in the citadel.') }}
                            </flux:text>

                            <flux:text>{{ __('Learn more in') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/category/special-monsters"
                                           external>
                                    {{ __('our wiki') }}
                                </flux:link>
                                {{ __('or track your next prey in the') }}
                                <flux:link variant="ghost"
                                           :href="route('schedule')"
                                           wire:navigate.hover>
                                    {{ __('event schedule.') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-home.feature-picture src="images/essentials/special-monsters"
                                                alt="Special monsters poster image"/>
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="rewards">
                <x-home.feature-split type="image">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Daily Rewards') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('Day after day, you can grab rewards to complement your gameplay - jewels, mysterious item bags... you name it - all at your fingertips.') }}
                            </flux:text>

                            <flux:text>{{ __('Are you curious?') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/gameplay-systems/daily-reward"
                                           external>
                                    {{ __('Learn more!') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-home.feature-picture src="images/essentials/daily-rewards"
                                                alt="Daily rewards in-game screen"/>
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>

            <flux:tab.panel name="helper">
                <x-home.feature-split type="image">
                    <x-slot:text>
                        <flux:heading size="lg">
                            {{ __('Helper') }}
                        </flux:heading>

                        <div class="space-y-2">
                            <flux:text>
                                {{  __('A powerful assistance system that makes your gaming experience smooth and efficient.') }}
                            </flux:text>

                            <flux:text>
                                {{  __("Keep farming even with your game minimized or closed - our helper takes care of your character while you're away.") }}
                            </flux:text>

                            <flux:text>{{ __('Learn how to pilot the') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/client-features/mu-helper"
                                           external>
                                    {{ __('mu helper') }}
                                </flux:link>
                                {{ __('and how to make use of the') }}
                                <flux:link variant="ghost"
                                           href="https://wiki.lotusmu.org/client-features/mu-helper/#off-helper"
                                           external>
                                    {{ __('off helper.') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    </x-slot:text>

                    <x-slot:media>
                        <x-home.feature-picture src="images/essentials/mu-helper" alt="Mu Helper dialog in-game"/>
                    </x-slot:media>
                </x-home.feature-split>
            </flux:tab.panel>
        </flux:tab.group>
    </x-home.wrapper>
</section>
