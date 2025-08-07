@php
    use App\Enums\Game\BankItem;
    use App\Enums\Partner\PartnerLevel;
    use App\Models\Partner\PartnerFarmPackage;
    use Livewire\Attributes\Layout;
    use Livewire\Volt\Component;
@endphp

<div class="space-y-6">
    <header>
        <flux:heading size="xl" level="1">
            {{ __('Partner Program') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Join our content creator program and earn tokens through your streams and videos.') }}
        </flux:subheading>
    </header>

    <!-- Hero Benefits -->
    <flux:card class="space-y-6">
        <div>
            <flux:heading size="lg" level="2">{{ __('Why Join Our Partner Program?') }}</flux:heading>
            <flux:subheading class="text-lg">
                {{ __('Turn your content creation into a rewarding experience for both you and your audience.') }}
            </flux:subheading>
        </div>

        <!-- Partner Benefits -->
        <flux:card>
            <flux:heading size="lg" level="3">
                {{ __('Partner Benefits') }}
            </flux:heading>

            <div class="mt-4 space-y-3">
                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.megaphone variant="solid" class="text-purple-500 flex-shrink-0"/>
                            <span>{{ __('Featured Content') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Your streams and videos featured on our website.') }}
                        </flux:subheading>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.currency-dollar variant="solid" class="text-green-500 flex-shrink-0"/>
                            <span>{{ __('Earn Tokens') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Get 10-30% of tokens from donations made with your promo code.') }}
                        </flux:subheading>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.fire variant="solid" class="text-yellow-500 flex-shrink-0"/>
                            <span>{{ __('VIP Status') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Weekly VIP rewards and exclusive Discord permissions.') }}
                        </flux:subheading>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.gift variant="solid" class="text-blue-500 flex-shrink-0"/>
                            <span>{{ __('Farm Rewards') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Bonus farm rewards based on your partner level.') }}
                        </flux:subheading>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Audience Benefits -->
        <flux:card>
            <flux:heading size="lg" level="3">
                {{ __('Your Audience Benefits') }}
            </flux:heading>

            <div class="mt-4 space-y-3">
                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.gift variant="solid" class="text-green-500 flex-shrink-0"/>
                            <span>{{ __('Bonus Tokens') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Your viewers get extra tokens when donating with your promo code.') }}
                        </flux:subheading>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.heart variant="solid" class="text-red-500 flex-shrink-0"/>
                            <span>{{ __('Support Their Creator') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('They help their favorite creator earn tokens while donating.') }}
                        </flux:subheading>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div>
                        <flux:heading class="flex items-center gap-2">
                            <flux:icon.sparkles variant="solid" class="text-purple-500 flex-shrink-0"/>
                            <span>{{ __('Exclusive Content') }}</span>
                        </flux:heading>
                        <flux:subheading>
                            {{ __('Access to partner-exclusive events and giveaways.') }}
                        </flux:subheading>
                    </div>
                </div>
            </div>
        </flux:card>
    </flux:card>

    <!-- How It Works -->
    <flux:card>
        <flux:heading size="lg">{{ __('How It Works') }}</flux:heading>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <flux:text class="!font-bold !text-blue-600">1</flux:text>
                </div>
                <flux:heading>{{ __('Apply') }}</flux:heading>
                <flux:subheading>{{ __('Submit your application.') }}</flux:subheading>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <flux:text class="!font-bold !text-green-600">2</flux:text>
                </div>
                <flux:heading>{{ __('Get Approved') }}</flux:heading>
                <flux:subheading>{{ __('Our team reviews your application.') }}</flux:subheading>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <flux:text class="!font-bold !text-purple-600">3</flux:text>
                </div>
                <flux:heading>{{ __('Create Content') }}</flux:heading>
                <flux:subheading>{{ __('Stream or make videos.') }}</flux:subheading>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <flux:text class="!font-bold !text-orange-600">4</flux:text>
                </div>
                <flux:heading>{{ __('Earn Rewards') }}</flux:heading>
                <flux:subheading>{{ __('Get tokens and weekly rewards.') }}</flux:subheading>
            </div>
        </div>
    </flux:card>

    <!-- Requirements -->
    <flux:card>
        <flux:heading size="lg">{{ __('Requirements') }}</flux:heading>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <flux:heading>{{ __('Content Requirements') }}</flux:heading>
                <flux:text class="mt-2">
                    • {{ __('Regular streaming or video uploads.') }}<br>
                    • {{ __('Friendly content.') }}<br>
                    • {{ __('Active engagement with your audience.') }}<br>
                    • {{ __('Willingness to promote our platform.') }}
                </flux:text>
            </div>

            <div>
                <flux:heading>{{ __('Technical Requirements') }}</flux:heading>
                <flux:text class="mt-2">
                    • {{ __('Active Discord account.') }}<br>
                    • {{ __('Prior content creation experience') }}<br>
                    • {{ __('Consistent upload/streaming schedule.') }}
                </flux:text>
            </div>
        </div>
    </flux:card>

    <!-- Partner Levels -->
    <flux:card>
        <flux:heading size="lg">{{ __('Partner Levels & Rewards') }}</flux:heading>

        <flux:table class="mt-6">
            <flux:columns>
                <flux:column>{{ __('Level') }}</flux:column>
                <flux:column>{{ __('Token Percentage') }}</flux:column>
                <flux:column>{{ __('Weekly Farm Rewards') }}</flux:column>
            </flux:columns>

            <flux:rows>
                @foreach(PartnerLevel::cases() as $level)
                    @php
                        $farmPackage = PartnerFarmPackage::active()->forLevel($level)->first();
                    @endphp
                    <flux:row>
                        <flux:cell>
                            <flux:badge size="sm"
                                        inset="top bottom"
                                        color="{{ $level->badgeColor() }}">{{ $level->getLabel() }}</flux:badge>
                        </flux:cell>

                        <flux:cell>{{ $level->getTokenPercentage() }}%</flux:cell>

                        <flux:cell>
                            @if($farmPackage)
                                @if($farmPackage->items && is_array($farmPackage->items))
                                    <flux:text size="sm" class="mt-1">
                                        @foreach($farmPackage->items as $item)
                                            @php
                                                $bankItem = BankItem::tryFrom($item['item_index'] ?? null);
                                                $itemName = $bankItem ? $bankItem->getName() : 'Unknown Item';
                                                $level = $item['item_level'] ?? 0;
                                                $quantity = $item['quantity'] ?? 1;

                                                // Handle special case for Loch's Feather/Monarch's Crest
                                                if ($bankItem === BankItem::LOCHS_FEATHER && $level === 1) {
                                                    $itemName = "Monarch's Crest";
                                                }
                                            @endphp
                                            <div>{{ $quantity }}
                                                x {{ $itemName }}{{ $level > 0 && $bankItem !== BankItem::LOCHS_FEATHER ? " (Level $level)" : '' }}</div>
                                        @endforeach
                                    </flux:text>
                                @endif
                            @else
                                <flux:text class="text-gray-500">{{ __('No package configured') }}</flux:text>
                            @endif
                        </flux:cell>
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
    </flux:card>

    <!-- CTA -->
    <div
        class="p-8 bg-gradient-to-r from-purple-600/15 to-blue-600/15 dark:from-purple-600/30 dark:to-blue-600/30 backdrop-blur-lg rounded-xl border border-purple-500/30">
        <flux:heading size="lg">
            {{ __('Turn Your Content Into Rewards') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Be among the first creators to join our exclusive partner program!') }}
        </flux:subheading>

        <flux:button
            href="{{ route('partners.apply') }}"
            variant="primary"
            icon="rocket-launch"
            class="mt-8"
        >
            {{ __('Apply Now') }}
        </flux:button>
    </div>
</div>
