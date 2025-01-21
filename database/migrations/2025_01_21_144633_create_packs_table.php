<?php

use App\Enums\Content\Catalog\PackTier;
use App\Enums\Utility\ResourceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packs', function (Blueprint $table) {
            $table->id();
            $table->integer('character_class');
            $table->enum('tier', collect(PackTier::cases())->map(fn ($case) => $case->value)->toArray());
            $table->string('image_path');
            $table->boolean('has_level')->default(false);
            $table->integer('level')->nullable();
            $table->boolean('has_additional')->default(false);
            $table->integer('additional')->nullable();
            $table->boolean('has_luck')->default(false);
            $table->boolean('has_skill')->default(false);
            $table->integer('price');
            $table->enum('resource', collect(ResourceType::cases())->map(fn ($case) => $case->value)->toArray())
                ->default(ResourceType::CREDITS->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
