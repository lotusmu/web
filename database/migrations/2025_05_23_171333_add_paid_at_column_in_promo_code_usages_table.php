<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_code_usages', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('promo_code_usages', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
};
