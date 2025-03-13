<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_discord_popup_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('joined_discord')->default(false);
            $table->boolean('never_show_again')->default(false);
            $table->timestamp('last_shown_at')->nullable();
            $table->timestamp('last_declined_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_discord_popup_preferences');
    }
};
