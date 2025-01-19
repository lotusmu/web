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
            $table->json('stats')->nullable(); // ["+200 defense", "+200 attack", etc]
            $table->string('image_path');
            $table->json('duration_prices'); // [{duration: 7, price: 100}, {duration: 14, price: 200}]
            $table->enum('resource', collect(ResourceType::cases())->map(fn ($case) => $case->value)->toArray())
                ->default(ResourceType::CREDITS->value);
            $table->boolean('is_bundle')->default(false);
            $table->json('bundle_items')->nullable(); // For bundles: array of buff names
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buffs');
    }
};
