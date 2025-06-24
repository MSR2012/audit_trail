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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 60);
                $table->string('email', 60)->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password', 70);
                $table->unsignedTinyInteger('role')->default(0)->comment('0 - User, 1 - Admin');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
