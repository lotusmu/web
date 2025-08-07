<?php

namespace App\Livewire\Pages\Guest\Rankings\Guilds;

use App\Enums\Utility\RankingScoreType;
use App\Livewire\BaseComponent;
use App\Models\Game\Guild;
use App\Models\Game\Ranking\Event;
use App\Models\Game\Ranking\Hunter;
use Livewire\Attributes\Computed;

class ScoreModal extends BaseComponent
{
    public Guild $guild;

    public RankingScoreType $type;

    #[Computed]
    public function scores()
    {
        if ($this->type === RankingScoreType::EVENTS) {
            return Event::query()
                ->select([
                    'EventID',
                    'EventName',
                    'PointsPerWin',
                    \DB::raw('SUM(WinCount) as WinCount'),
                    \DB::raw('SUM(TotalPoints) as TotalPoints'),
                ])
                ->join('GuildMember', 'RankingEvents.Name', '=', 'GuildMember.Name')
                ->where('GuildMember.G_Name', $this->guild->G_Name)
                ->groupBy('EventID', 'EventName', 'PointsPerWin')
                ->with('event:EventID,EventName,image_path')
                ->get()
                ->sortByDesc('TotalPoints')
                ->map(fn ($score) => [
                    'name' => $score->EventName,
                    'count' => number_format($score->WinCount),
                    'points' => number_format($score->PointsPerWin),
                    'total_points' => number_format($score->TotalPoints),
                    'total_points_raw' => $score->TotalPoints,
                    'count_label' => __('wins'),
                    'image' => $score->event?->image_path ? asset($score->event->image_path) : null,
                ]);
        }

        return Hunter::query()
            ->select([
                'MonsterName',
                'MonsterClass',
                'PointsPerKill',
                \DB::raw('SUM(KillCount) as KillCount'),
                \DB::raw('SUM(TotalPoints) as TotalPoints'),
            ])
            ->join('GuildMember', 'RankingHunters.Name', '=', 'GuildMember.Name')
            ->where('GuildMember.G_Name', $this->guild->G_Name)
            ->groupBy('MonsterName', 'MonsterClass', 'PointsPerKill')
            ->with('monster:MonsterClass,MonsterName,image_path')
            ->get()
            ->sortByDesc('TotalPoints')
            ->map(fn ($score) => [
                'name' => $score->MonsterName,
                'count' => number_format($score->KillCount),
                'points' => number_format($score->PointsPerKill),
                'total_points' => number_format($score->TotalPoints),
                'total_points_raw' => $score->TotalPoints,
                'count_label' => __('kills'),
                'image' => $score->monster?->image_path ? asset($score->monster->image_path) : null,
            ]);
    }

    #[Computed]
    public function totalScore(): string
    {
        return number_format(
            $this->scores->sum('total_points_raw')
        );
    }

    #[Computed]
    public function formatScore($score): string
    {
        return "{$score['count']} {$score['count_label']} × {$score['points']} ".__('points');
    }

    public function placeholder()
    {
        $rows = match ($this->type) {
            RankingScoreType::EVENTS => 4,
            RankingScoreType::HUNTERS => 10,
        };

        return view('livewire.pages.guest.rankings.placeholders.modal', [
            'rows' => $rows,
        ]);
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.guilds.score-modal';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
