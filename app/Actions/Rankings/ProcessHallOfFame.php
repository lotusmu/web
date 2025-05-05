<?php

namespace App\Actions\Rankings;

use App\Models\Game\Character;
use App\Models\Game\Ranking\HallOfFame;
use Exception;

class ProcessHallOfFame
{
    public function handle(): array
    {
        $winners = HallOfFame::all();

        $processed = [
            'updated' => 0,
            'failed' => [],
        ];

        foreach ($winners as $winner) {
            foreach (['dk', 'dw', 'fe', 'mg', 'dl'] as $column) {
                if (! empty($winner->$column)) {
                    $this->incrementHofWins($winner->$column, $processed);
                }
            }
        }

        return $processed;
    }

    private function incrementHofWins(string $characterName, array &$processed): void
    {
        try {
            Character::where('Name', $characterName)
                ->increment('HofWins');

            $processed['updated']++;
        } catch (Exception $e) {
            $processed['failed'][] = [
                'character' => $characterName,
                'error' => $e->getMessage(),
            ];
        }
    }
}
