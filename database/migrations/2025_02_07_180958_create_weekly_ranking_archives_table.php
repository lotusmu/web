<?php

use App\Models\Utility\GameServer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_ranking_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GameServer::class)->constrained()->cascadeOnDelete();
            $table->string('type'); // 'event' or 'hunter'
            $table->date('cycle_start');
            $table->date('cycle_end');
            $table->integer('rank');
            $table->string('character_name');
            $table->integer('score');
            $table->json('rewards_given');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_ranking_archives');
    }
};
