<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watchlist', function (Blueprint $table) {
            $table->id();
            // Naka-link na ngayon nang direkta sa users table at mag-a-auto-delete kung sakaling burahin ang account
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('movie_id');
            $table->string('title')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('type')->default('movie');
            $table->string('status')->default('plan'); // 'plan' o 'watched'
            $table->timestamps();

            // Sinisigurado nito na iisang record lang per movie bawat user para maiwasan ang duplicate
            $table->unique(['user_id', 'movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist');
    }
};