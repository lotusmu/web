<?php

use App\Models\Utility\GameServer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('castle_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GameServer::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('total_prize_pool')->default(0);
            $table->unsignedBigInteger('remaining_prize_pool')->default(0);
            $table->unsignedInteger('distribution_weeks')->default(1);
            $table->unsignedInteger('weekly_amount')->storedAs('total_prize_pool / distribution_weeks');
            $table->timestamp('period_starts_at');
            $table->timestamp('period_ends_at');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('is_active');
            $table->index(['is_active', 'period_starts_at', 'period_ends_at']);
            $table->index('remaining_prize_pool');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('castle_prizes');
    }
};
