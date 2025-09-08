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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('type', ['house', 'apartment', 'land', 'office']);
            $table->enum('status', ['active', 'pending', 'archived'])->default('pending');
            $table->string('city', 100);
            $table->string('region', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->decimal('latitude', 100, 500)->nullable();
            $table->decimal('longitude', 100, 500)->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
