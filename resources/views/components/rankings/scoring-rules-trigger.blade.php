@props(['scoreType'])

<flux:modal.trigger :name="$scoreType->value . '-scoring'">
    <flux:button icon="information-circle" size="sm" inset="top bottom" variant="ghost"/>
</flux:modal.trigger>
