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
       Schema::create('room_images', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('room_id');
        $table->string('image_path'); 
        $table->timestamps();

        $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_images');
    }
};
