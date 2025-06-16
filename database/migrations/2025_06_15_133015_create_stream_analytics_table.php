<?php

use App\Models\Partner\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
            $table->string('provider'); // twitch, youtube, facebook
            $table->date('date');
            $table->decimal('total_hours_streamed', 5, 2)->default(0); // Hours with 2 decimal places
            $table->unsignedInteger('total_viewers')->default(0);
            $table->unsignedTinyInteger('stream_count')->default(0);
            $table->decimal('average_stream_duration', 4, 2)->default(0); // Hours
            $table->decimal('scheduled_vs_actual_hours', 5, 2)->default(0); // Consistency score
            $table->unsignedTinyInteger('days_streamed_this_week')->default(0);
            $table->unsignedTinyInteger('longest_streak_days')->default(0);
            $table->decimal('viewer_growth_rate', 5, 2)->default(0); // Percentage change
            $table->decimal('chat_activity_score', 5, 2)->default(0); // Chat messages per viewer
            $table->timestamps();

            $table->unique(['partner_id', 'provider', 'date']);
            $table->index(['provider', 'date']);
            $table->index(['date']);
            $table->index(['viewer_growth_rate']);
            $table->index(['total_hours_streamed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stream_analytics');
    }
};
