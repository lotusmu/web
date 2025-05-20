<?php

use App\Models\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('promo_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->timestamp('vip_until')->nullable();
            $table->string('status')->default('active');
            $table->json('platforms');
            $table->json('channels');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
