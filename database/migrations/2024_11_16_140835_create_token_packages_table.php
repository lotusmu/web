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
        Schema::create('token_packages', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_product_id');
            $table->string('stripe_price_id');
            $table->string('name');
            $table->integer('tokens_amount');
            $table->decimal('price', 8, 2);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_packages');
    }
};
