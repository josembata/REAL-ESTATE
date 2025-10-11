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
          Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('provider', 100); // mpesa, tigopesa, card
        $table->string('provider_payment_id')->nullable(); // transaction reference
        $table->decimal('amount', 14, 2);
        $table->string('currency', 3)->default('USD');
        $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
