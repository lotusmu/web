<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('agreed_at')->nullable()->after('expires_at');
        });

        // Set agreed_at for existing orders to their created_at time
        DB::table('orders')->update(['agreed_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('agreed_at');
        });
    }
};
