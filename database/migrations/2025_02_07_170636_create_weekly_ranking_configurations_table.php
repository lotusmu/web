<?php

use App\Models\Utility\GameServer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_ranking_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GameServer::class)->constrained()->cascadeOnDelete();
            $table->date('first_cycle_start');
            $table->tinyInteger('reset_day_of_week');
            $table->string('reset_time');
            $table->boolean('is_enabled')->default(false);
            $table->timestamp('last_processing_start')->nullable();
            $table->timestamp('last_successful_processing')->nullable();
            $table->json('processing_state')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_ranking_configurations');
    }
};
