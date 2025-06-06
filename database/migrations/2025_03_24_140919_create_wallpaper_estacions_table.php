<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallpaper_estacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallpaper_id')->constrained('wallpapers')->onDelete('cascade');
            $table->foreignId('estacion_id')->constrained('estaciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallpaper_estacion');
    }
};
