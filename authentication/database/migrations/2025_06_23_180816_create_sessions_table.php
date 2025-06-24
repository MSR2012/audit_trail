<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->string('uuid')->unique();
                $table->string('ip_address', 20);
                $table->string('user_agent', 255);
                $table->text('token');
                $table->dateTime('token_expires_at');
                $table->text('refresh_token');
                $table->dateTime('refresh_token_expires_at');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
