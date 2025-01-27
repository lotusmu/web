<?php

use Livewire\Volt\Component;

new class extends Component {
//
} ?>

<div class="mb-8">
    <flux:input placeholder="Search..." icon="magnifying-glass"
                class="max-w-sm mx-auto">
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"/>
        </x-slot>
    </flux:input>
</div>
