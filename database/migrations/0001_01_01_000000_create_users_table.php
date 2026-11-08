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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();[cite: 15]
            $table->string('password');[cite: 15]
            $table->boolean('is_admin')->default(0);
            $table->timestamps();[cite: 15]
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();[cite: 15]
            $table->string('token');[cite: 15]
            $table->timestamp('created_at')->nullable();[cite: 15]
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();[cite: 15]
            $table->foreignId('user_id')->nullable()->index();[cite: 15]
            $table->string('ip_address', 45)->nullable();[cite: 15]
            $table->text('user_agent')->nullable();[cite: 15]
            $table->longText('payload');[cite: 15]
            $table->integer('last_activity')->index();[cite: 15]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');[cite: 15]
        Schema::dropIfExists('password_reset_tokens');[cite: 15]
        Schema::dropIfExists('sessions');[cite: 15]
    }
};