<?php

use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_ranking_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WeeklyRankingConfiguration::class)->constrained()->cascadeOnDelete();
            $table->integer('position_from');
            $table->integer('position_to');
            $table->json('rewards'); // [{type: "zen", amount: 1000000}, {type: "credits", amount: 100}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_ranking_rewards');
    }
};
