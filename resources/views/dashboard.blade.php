<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <flux:checkbox.group label="Subscription preferences">
                        <flux:checkbox checked
                                       value="newsletter"
                                       label="Newsletter"
                                       description="Receive our monthly newsletter with the latest updates and offers."
                        />
                        <flux:checkbox
                            value="updates"
                            label="Product updates"
                            description="Stay informed about new features and product updates."
                        />
                        <flux:checkbox
                            value="invitations"
                            label="Event invitations"
                            description="Get invitations to our exclusive events and webinars."
                        />
                    </flux:checkbox.group>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
