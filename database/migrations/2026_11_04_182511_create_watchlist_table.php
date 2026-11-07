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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('movie_id');
            $table->string('title')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('type')->default('movie');
            $table->string('status')->default('plan'); // 'plan' o 'watched'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist');
    }
};