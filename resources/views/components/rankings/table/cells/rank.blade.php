@props(['paginator', 'loop'])

<flux:cell>
    <span>
        {{ ($paginator->currentPage() - 1) * 10 + $loop->iteration }}.
    </span>
</flux:cell>
