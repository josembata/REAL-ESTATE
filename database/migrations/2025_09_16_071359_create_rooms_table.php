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
      Schema::create('rooms', function (Blueprint $table) {
    $table->id('room_id');
    $table->unsignedBigInteger('unit_id');
    $table->string('room_name', 100);
    $table->enum('room_type', ['bedroom','bathroom','office','shop','warehouse','other']);
    $table->decimal('size_sqft', 12, 2)->nullable();
    $table->decimal('price', 12, 2)->nullable();
    $table->enum('availability_status', ['available','occupied','reserved'])->default('available');
    $table->timestamps();

    $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
