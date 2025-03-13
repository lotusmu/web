<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scheduled_events', function (Blueprint $table) {
            $table->integer('duration_minutes')->nullable()->after('interval_minutes');
        });
    }
};
