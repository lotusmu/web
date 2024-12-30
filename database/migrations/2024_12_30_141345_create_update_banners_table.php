<?php

use App\Enums\Utility\UpdateBannerType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_banners', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(UpdateBannerType::cases(), 'value'));
            $table->string('content');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_banners');
    }
};
