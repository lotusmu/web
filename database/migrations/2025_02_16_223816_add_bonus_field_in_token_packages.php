<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('token_packages', function (Blueprint $table) {
            $table->integer('bonus_rate')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('token_packages', function (Blueprint $table) {
            $table->dropColumn('bonus_rate');
        });
    }
};
