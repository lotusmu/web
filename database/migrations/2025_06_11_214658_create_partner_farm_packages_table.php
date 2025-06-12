<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_farm_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Level 1 Weekly Farm"
            $table->unsignedTinyInteger('partner_level'); // 1, 2, 3, 4, 5
            $table->json('items'); // [{"item_index": 7181, "item_level": 0, "quantity": 100}]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_farm_packages');
    }
};
