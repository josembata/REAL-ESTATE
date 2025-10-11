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
       Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('property_id')->constrained()->onDelete('cascade');
        $table->foreignId('buyer_id')->nullable()->references('id')->on('owners')->onDelete('set null');
        $table->foreignId('seller_id')->nullable()->references('id')->on('owners')->onDelete('set null');
        $table->enum('transaction_type', ['purchase', 'lease', 'transfer', 'mortgage']);
        $table->decimal('price', 15,2)->nullable();
        $table->date('transaction_date');
        $table->string('document_path')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
