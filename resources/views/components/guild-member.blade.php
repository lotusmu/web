@php use App\Enums\Game\GuildMemberStatus; @endphp
@props([
    'guildMember' => null,
])

@php
    $position = $guildMember->G_Status;

    $classes = Flux::classes()
        ->add('text-sm font-medium')
        ->add(match ($position) {
                GuildMemberStatus::GuildMaster => 'text-red-600 dark:text-red-400',
                GuildMemberStatus::AssistantGuildMaster => 'text-orange-600 dark:text-orange-400',
                GuildMemberStatus::BattleMaster => 'text-sky-600 dark:text-sky-400',
                GuildMemberStatus::GuildMember => 'text-zinc-800 dark:text-white',
            })
            ;
@endphp

@if($guildMember)
    <div {{ $attributes->class($classes) }}>
        {{ $guildMember->G_Status->getLabel() }}
    </div>
@else
    <x-empty-cell/>
@endif
