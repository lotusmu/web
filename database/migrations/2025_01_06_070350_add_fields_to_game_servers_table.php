<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->string('server_version');
            $table->integer('max_resets');
            $table->integer('starting_resets');
            $table->bigInteger('reset_zen');
            $table->bigInteger('clear_pk_zen');
        });
    }

    public function down(): void
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->dropColumn([
                'server_version',
                'max_resets',
                'starting_resets',
                'reset_zen',
                'clear_pk_zen',
            ]);
        });
    }
};
