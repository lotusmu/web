<?php

use App\Enums\Map;
use Filament\Support\Contracts\HasLabel;

describe('Map Enum', function () {
    it('has the correct number of enum cases', function () {
        expect(Map::cases())->toHaveCount(108);
    });

    it('has the correct enum values and labels', function () {
        $expectedMaps = [
            'Lorencia' => ['value' => 0, 'label' => 'Lorencia'],
            'Dungeon' => ['value' => 1, 'label' => 'Dungeon'],
            'Devias' => ['value' => 2, 'label' => 'Devias'],
            'Noria' => ['value' => 3, 'label' => 'Noria'],
            'LostTower' => ['value' => 4, 'label' => 'Lost Tower'],
            'Exile' => ['value' => 5, 'label' => 'Exile'],
            'VIPArena' => ['value' => 6, 'label' => 'VIP Arena'],
            'Atlans' => ['value' => 7, 'label' => 'Atlans'],
            'Tarkan' => ['value' => 8, 'label' => 'Tarkan'],
            'DevilSquareI_IV' => ['value' => 9, 'label' => 'Devil Square I-IV'],
            'Icarus' => ['value' => 10, 'label' => 'Icarus'],
            'BloodCastleI' => ['value' => 11, 'label' => 'Blood Castle I'],
            'BloodCastleVIII' => ['value' => 52, 'label' => 'Blood Castle VIII'],
            'ChaosCastleI' => ['value' => 18, 'label' => 'Chaos Castle I'],
            'ChaosCastleVII' => ['value' => 53, 'label' => 'Chaos Castle VII'],
            'KalimaI' => ['value' => 24, 'label' => 'Kalima I'],
            'KalimaVII' => ['value' => 36, 'label' => 'Kalima VII'],
            'ValleyOfLoren' => ['value' => 30, 'label' => 'Valley of Loren'],
            'LandOfTrial' => ['value' => 31, 'label' => 'Land of Trial'],
            'Kanturu' => ['value' => 37, 'label' => 'Kanturu'],
            'KanturuRuins' => ['value' => 38, 'label' => 'Kanturu Ruins'],
            'IllusionTempleI' => ['value' => 45, 'label' => 'Illusion Temple I'],
            'IllusionTempleVI' => ['value' => 50, 'label' => 'Illusion Temple VI'],
            'Elbeland' => ['value' => 51, 'label' => 'Elbeland'],
            'SwampOfPeace' => ['value' => 56, 'label' => 'Swamp of Peace'],
            'Karutan' => ['value' => 80, 'label' => 'Karutan'],
            'Karutan2' => ['value' => 81, 'label' => 'Karutan 2'],
            'ChaosCastleBattle' => ['value' => 97, 'label' => 'Chaos Castle Battle'],
            'IllusionTempleLeague' => ['value' => 98, 'label' => 'Illusion Temple League'],
            'TormentedSquareOfTheFittest' => ['value' => 102, 'label' => 'Tormented Square of the Fittest'],
            'Nars' => ['value' => 110, 'label' => 'Nars'],
            'Ferea' => ['value' => 112, 'label' => 'Ferea'],
            'NixieLake' => ['value' => 113, 'label' => 'Nixie Lake'],
            'SwampOfDarkness' => ['value' => 122, 'label' => 'Swamp of Darkness'],
            'BurningKethotum' => ['value' => 136, 'label' => 'Burning Kethotum'],
        ];

        foreach ($expectedMaps as $mapName => $details) {
            $map = constant(Map::class.'::'.$mapName);
            expect($map->value)->toBe($details['value'])
                ->and($map->getLabel())->toBe($details['label']);
        }
    });

    it('implements necessary interfaces', function () {
        expect(class_implements(Map::class))->toContain(HasLabel::class);
    });
});
