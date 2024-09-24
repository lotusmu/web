<?php

use function Livewire\Volt\{state};

state();

?>

<div>
    <flux:heading>Radio Group</flux:heading>
    <flux:subheading>
        Here are some sweet sweet radio buttons, staying with a beautiful flux card.
    </flux:subheading>

    <flux:card class="mt-6 max-w-xl">
        <flux:fieldset>
            <flux:legend>Role</flux:legend>

            <flux:radio.group>
                <flux:radio
                    value="administrator"
                    label="Administrator"
                    description="Administrator users can perform any action."
                    checked
                />
                <flux:radio
                    value="editor"
                    label="Editor"
                    description="Editor users have the ability to read, create, and update."
                />
                <flux:radio
                    value="viewer"
                    label="Viewer"
                    description="Viewer users only have the ability to read. Create, and update are restricted."
                />
            </flux:radio.group>
        </flux:fieldset>
    </flux:card>

    <div class="max-w-xl space-y-2 mt-6">
        <flux:input icon="user"/>
        <flux:input icon="lock-closed" type="password"/>
    </div>
</div>
