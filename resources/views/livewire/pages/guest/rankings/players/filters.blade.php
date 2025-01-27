<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $class = 'all';

    public array $classes = [
        'all' => ['label' => 'All', 'image' => 'avatar.jpg'],
        'dk'  => ['label' => 'Knights', 'image' => 'dk.jpg'],
        'dw'  => ['label' => 'Wizards', 'image' => 'dw.jpg'],
        'elf' => ['label' => 'Elves', 'image' => 'elf.jpg'],
        'mg'  => ['label' => 'Gladiators', 'image' => 'mg.jpg'],
        'dl'  => ['label' => 'Lords', 'image' => 'dl.jpg'],
    ];
} ?>

<div class="mb-8">
    <div class="sm:flex items-center justify-center gap-8 mb-8 hidden">
        @foreach($classes as $key => $data)
            <button wire:click="$set('class', '{{ $key }}')"
                    class="flex flex-col items-center justify-center {{ $class === $key ? 'opacity-100' : 'opacity-70' }} hover:opacity-100 transition-all duration-200">
                <img src="{{ asset("images/character_classes/{$data['image']}") }}"
                     alt="{{ $data['label'] }}"
                     class="w-12 rounded-xl mb-2">
                <flux:text size="sm">
                    {{ $data['label'] }}
                </flux:text>
            </button>
        @endforeach
    </div>

    <div class="sm:hidden mb-8">
        <flux:select wire:model.live="class" variant="listbox" placeholder="Select class...">
            @foreach($classes as $key => $data)
                <flux:option value="{{ $key }}">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset("images/character_classes/{$data['image']}") }}"
                             alt="{{ $data['label'] }}"
                             class="w-6 h-6 rounded-lg">
                        {{ $data['label'] }}
                    </div>
                </flux:option>
            @endforeach
        </flux:select>
    </div>

    <flux:input placeholder="Search..." icon="magnifying-glass"
                class="max-w-sm mx-auto">
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"/>
        </x-slot>
    </flux:input>
</div>
