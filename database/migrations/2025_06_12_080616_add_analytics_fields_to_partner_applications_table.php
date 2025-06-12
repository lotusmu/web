<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_applications', function (Blueprint $table) {
            $table->unsignedSmallInteger('content_creation_months')->nullable()->after('videos_per_week');
            $table->unsignedInteger('average_live_viewers')->nullable()->after('content_creation_months');
            $table->unsignedInteger('average_video_views')->nullable()->after('average_live_viewers');
        });
    }

    public function down(): void
    {
        Schema::table('partner_applications', function (Blueprint $table) {
            $table->dropColumn([
                'content_creation_months',
                'average_live_viewers',
                'average_video_views',
            ]);
        });
    }
};
