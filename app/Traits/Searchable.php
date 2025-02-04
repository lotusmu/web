<?php

namespace App\Traits;

trait Searchable
{
    public string $search = '';

    protected function searchCharacter($query)
    {
        if ($this->search === '') {
            return $query;
        }

        return $query->where(function ($query) {
            $query->where('Name', 'like', $this->search.'%')
                ->orWhereHas('guildMember.guild', function ($query) {
                    $query->where('G_Name', 'like', $this->search.'%');
                });
        });
    }

    protected function searchGuild($query)
    {
        return $this->search === ''
            ? $query
            : $query->where('G_Name', 'like', $this->search.'%');
    }

    public function updatedSearchable($property): void
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }
}
