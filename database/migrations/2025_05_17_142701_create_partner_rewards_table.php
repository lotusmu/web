<?php

use App\Models\Partner\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
            $table->string('type'); // 'farm', 'commission', etc.
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('week_number');
            $table->unsignedSmallInteger('year');
            $table->string('description')->nullable();
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_rewards');
    }
};
