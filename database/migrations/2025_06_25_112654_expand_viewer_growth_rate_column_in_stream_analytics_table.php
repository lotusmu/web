<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stream_analytics', function (Blueprint $table) {
            $table->decimal('viewer_growth_rate', 8, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stream_analytics', function (Blueprint $table) {
            $table->decimal('viewer_growth_rate', 5, 2)->default(0)->change();
        });
    }
};
