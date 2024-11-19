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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('token_package_id')->constrained();
            $table->string('payment_provider');
            $table->string('payment_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->string('status');
            $table->json('payment_data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['payment_provider', 'payment_id']);

            $table->unique(['user_id', 'token_package_id', 'payment_id'], 'prevent_duplicate_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('prevent_duplicate_orders');
            $table->unique(['user_id', 'token_package_id', 'payment_id'], 'prevent_duplicate_orders');
        });
    }
};
