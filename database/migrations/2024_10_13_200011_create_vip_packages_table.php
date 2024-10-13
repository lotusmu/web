<?php

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
        Schema::create('vip_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level');
            $table->boolean('is_best_value')->default(false);
            $table->unsignedSmallInteger('duration');
            $table->unsignedSmallInteger('cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_packages');
    }
};
