<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->float('online_multiplier');
        });
    }

    public function down(): void
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->dropColumn([
                'online_multiplier',
            ]);
        });
    }
};
