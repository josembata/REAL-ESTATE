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
      Schema::create('invoice_bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
    $table->foreignId('booking_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 14, 2); // amount for this booking
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_bookings');
    }
};
