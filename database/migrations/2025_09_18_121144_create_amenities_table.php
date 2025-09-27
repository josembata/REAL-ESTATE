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
    Schema::create('amenities', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->unsignedBigInteger('category_id');
    $table->string('icon')->nullable(); // path to uploaded image
    $table->timestamps();

    $table->foreign('category_id')->references('id')->on('amenity_categories')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
