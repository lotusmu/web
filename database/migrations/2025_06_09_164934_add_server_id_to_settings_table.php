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
        Schema::table('settings', function (Blueprint $table) {
            $table->foreignIdFor(GameServer::class, 'server_id')->after('group');

            $table->dropUnique(['group']);

            $table->unique(['group', 'server_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['server_id']);

            $table->dropUnique(['group', 'server_id']);

            $table->dropColumn('server_id');

            $table->unique(['group']);
        });
    }
};
