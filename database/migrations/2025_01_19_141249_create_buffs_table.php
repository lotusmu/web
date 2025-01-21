<?php

use App\Enums\Utility\ResourceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('stats')->nullable();
            $table->string('image_path');
            $table->json('duration_prices');
            $table->enum('resource', collect(ResourceType::cases())->map(fn ($case) => $case->value)->toArray())
                ->default(ResourceType::CREDITS->value);
            $table->boolean('is_bundle')->default(false);
            $table->json('bundle_items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buffs');
    }
};
