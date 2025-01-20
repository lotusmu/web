<?php

use App\Enums\Content\Catalog\SupplyCategory;
use App\Enums\Utility\ResourceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 255);
            $table->string('image_path');
            $table->integer('price');
            $table->enum('category', collect(SupplyCategory::cases())->map(fn ($case) => $case->value)->toArray());
            $table->enum('resource', collect(ResourceType::cases())->map(fn ($case) => $case->value)->toArray())
                ->default(ResourceType::CREDITS->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
