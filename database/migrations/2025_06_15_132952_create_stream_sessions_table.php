<?php

use App\Models\Partner\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
            $table->string('provider'); // twitch, youtube, facebook
            $table->string('external_stream_id'); // Platform's stream ID
            $table->string('channel_name'); // streamer username/channel
            $table->string('title')->nullable();
            $table->string('game_category')->nullable();
            $table->string('language')->nullable();
            $table->json('stream_tags')->nullable(); // Platform-specific tags
            $table->boolean('mature_content')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('peak_viewers')->default(0);
            $table->unsignedInteger('average_viewers')->default(0);
            $table->unsignedTinyInteger('day_of_week'); // 1-7 (Monday-Sunday)
            $table->unsignedTinyInteger('hour_of_day'); // 0-23
            $table->string('stream_quality')->nullable(); // 1080p60, 720p30, etc.
            $table->timestamps();

            $table->index(['partner_id', 'provider']);
            $table->index(['provider', 'started_at']);
            $table->index(['day_of_week', 'hour_of_day']);
            $table->index(['ended_at']);
            $table->index(['partner_id', 'provider', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stream_sessions');
    }
};
