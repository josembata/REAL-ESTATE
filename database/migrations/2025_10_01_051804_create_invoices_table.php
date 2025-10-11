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
      Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // invoice belongs to customer
    $table->string('invoice_number')->unique();
    $table->decimal('amount_due', 14, 2)->default(0); // sum of all bookings
    $table->string('currency', 3)->default('USD');
    $table->enum('status', ['unpaid', 'partially_paid', 'paid', 'cancelled'])->default('unpaid');
    $table->timestamp('issued_at')->useCurrent();
    $table->timestamp('due_date')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
