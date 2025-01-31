@props(['type'])

<flux:modal.trigger :name="$type->value . '-scoring'">
    <flux:button icon="information-circle" size="sm" inset="top bottom" variant="ghost"/>
</flux:modal.trigger>
