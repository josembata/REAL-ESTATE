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
       Schema::create('room_price_plans', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('room_id');
    $table->unsignedBigInteger('category_id'); // link to price plan category 
    $table->decimal('price', 12, 2);
    $table->string('currency', 10)->default('USD');
    $table->timestamps();

    $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
    $table->foreign('category_id')->references('id')->on('price_plan_categories')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_price_plans');
    }
};
