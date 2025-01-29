<?php

namespace App\Traits;

trait Searchable
{
    public string $search = '';

    protected function searchCharacter($query)
    {
        return $this->search === ''
            ? $query
            : $query->where('Name', 'like', '%'.$this->search.'%');
    }

    protected function searchGuild($query)
    {
        return $this->search === ''
            ? $query
            : $query->where('G_Name', 'like', '%'.$this->search.'%');
    }

    public function updatedSearchable($property): void
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }
}
