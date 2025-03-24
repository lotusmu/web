<?php

use App\Models\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('referral_source')->nullable();
            $table->string('mmo_top_site')->nullable();
            $table->string('mu_online_forum')->nullable();
            $table->string('custom_source')->nullable();
            $table->boolean('completed')->default(false);
            $table->boolean('dismissed')->default(false);
            $table->timestamp('shown_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_surveys');
    }
};
