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
        Schema::create('ownerships', function (Blueprint $table) {
        $table->id();
        $table->foreignId('property_id')->constrained()->onDelete('cascade');
        $table->foreignId('owner_id')->constrained()->onDelete('cascade');
        $table->enum('ownership_type', ['freehold', 'leasehold', 'mortgage', 'joint'])->default('freehold');
        $table->decimal('share_percentage', 5,2)->default(100.00);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('document_path')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ownerships');
    }
};
