<?php

use App\Models\Utility\CastlePrize;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('castle_prize_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CastlePrize::class)->constrained()->cascadeOnDelete();
            $table->string('guild_name');
            $table->integer('total_members');
            $table->integer('distributed_amount');
            $table->integer('amount_per_member');
            $table->timestamps();

            $table->index('guild_name');
            $table->index('created_at');
            $table->index(['castle_prize_id', 'created_at']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('castle_prize_distributions');
    }
};
