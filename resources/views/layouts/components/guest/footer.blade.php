<footer aria-labelledby="footer-heading" class="relative">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    <div class="mx-auto max-w-7xl px-6 pb-8 pt-4 lg:px-8">
        <flux:separator class="mb-8"/>

        <div class="flex flex-col gap-16">
            <!-- Sitemap -->
            <div class="grid grid-cols-2 gap-y-10 lg:grid-cols-6 lg:gap-8">
                <x-brand
                    :logo_light="asset('images/brand/lotusmu-logotype.svg')"
                    :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
                    class="col-span-2"
                />

                <div
                    class="col-span-2 grid grid-cols-2 gap-x-8 gap-y-12 lg:col-span-4 lg:grid-cols-subgrid">

                    <!-- Information-->
                    <div class="space-y-8">
                        <flux:subheading>
                            {{__('Information')}}
                        </flux:subheading>

                        <ul class="space-y-3">
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Server Info') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Patch Notes') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Invasion Times') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="https://wiki.lotusmu.org/category/events"
                                           external>
                                    {{ __('Events') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="https://wiki.lotusmu.org/category/crafting"
                                           external>
                                    {{ __('Crafting') }}
                                </flux:link>
                            </li>
                        </ul>
                    </div>

                    <!-- How to start-->
                    <div class="space-y-8">
                        <flux:subheading>
                            {{ __('How to Start') }}
                        </flux:subheading>

                        <ul class="space-y-3">
                            <li>
                                <flux:link variant="subtle" :href="route('register')">
                                    {{ __('Registration') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Download') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="https://wiki.lotusmu.org" external>
                                    {{ __('Wiki') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle"
                                           href="https://wiki.lotusmu.org/category/game-client-features/"
                                           external>
                                    {{ __('Client Features') }}
                                </flux:link>
                            </li>
                        </ul>
                    </div>

                    <!-- Community-->
                    <div class="space-y-8">
                        <flux:subheading>
                            {{ __('Community') }}
                        </flux:subheading>

                        <ul class="space-y-3">
                            <li>
                                <flux:link variant="subtle" :href="route('news')">
                                    {{ __('News') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Facebook') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('YouTube') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Discord') }}
                                </flux:link>
                            </li>
                        </ul>
                    </div>

                    <!-- Support-->
                    <div class="space-y-8">
                        <flux:subheading>
                            {{ __('Support') }}
                        </flux:subheading>

                        <ul class="space-y-3">
                            <li>
                                <flux:link variant="subtle"
                                           :href="route('support')">
                                    {{__('Help Center')}}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Rules') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Privacy Policy') }}
                                </flux:link>
                            </li>
                            <li>
                                <flux:link variant="subtle" href="#">
                                    {{ __('Refund Policy') }}
                                </flux:link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Copyright & Socials -->
            <div class="flex max-sm:flex-col max-sm:items-start max-sm:space-y-2 items-center justify-between">
                <flux:text size="sm" class="leading-5 mt-0">
                    &copy; {{ date("Y") }} {{__('Lotus Mu')}}
                    ·
                    <flux:link variant="subtle" href="#">Terms of Service</flux:link>
                </flux:text>

                <div class="flex space-x-6 ">
                    <a href="#" class="text-zinc-500 dark:hover:text-zinc-400 hover:text-zinc-600">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 dark:hover:text-zinc-400 hover:text-zinc-600">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 dark:hover:text-zinc-400 hover:text-zinc-600">
                        <span class="sr-only">Discord</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M20.317 4.492c-1.53-.69-3.17-1.2-4.885-1.49a.075.075 0 0 0-.079.036c-.21.369-.444.85-.608 1.23a18.566 18.566 0 0 0-5.487 0 12.36 12.36 0 0 0-.617-1.23A.077.077 0 0 0 8.562 3c-1.714.29-3.354.8-4.885 1.491a.07.07 0 0 0-.032.027C.533 9.093-.32 13.555.099 17.961a.08.08 0 0 0 .031.055 20.03 20.03 0 0 0 5.993 2.98.078.078 0 0 0 .084-.026c.462-.62.874-1.275 1.226-1.963.021-.04.001-.088-.041-.104a13.201 13.201 0 0 1-1.872-.878.075.075 0 0 1-.008-.125c.126-.093.252-.19.372-.287a.075.075 0 0 1 .078-.01c3.927 1.764 8.18 1.764 12.061 0a.075.075 0 0 1 .079.009c.12.098.245.195.372.288a.075.075 0 0 1-.006.125c-.598.344-1.22.635-1.873.877a.075.075 0 0 0-.041.105c.36.687.772 1.341 1.225 1.962a.077.077 0 0 0 .084.028 19.963 19.963 0 0 0 6.002-2.981.076.076 0 0 0 .032-.054c.5-5.094-.838-9.52-3.549-13.442a.06.06 0 0 0-.031-.028zM8.02 15.278c-1.182 0-2.157-1.069-2.157-2.38 0-1.312.956-2.38 2.157-2.38 1.21 0 2.176 1.077 2.157 2.38 0 1.312-.956 2.38-2.157 2.38zm7.975 0c-1.183 0-2.157-1.069-2.157-2.38 0-1.312.955-2.38 2.157-2.38 1.21 0 2.176 1.077 2.157 2.38 0 1.312-.946 2.38-2.157 2.38z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
