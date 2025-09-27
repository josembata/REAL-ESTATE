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
       Schema::create('inquiries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('property_id')->constrained()->cascadeOnDelete();
    $table->foreignId('unit_id')->nullable()->constrained()->cascadeOnDelete();
    $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('agent_id')->nullable()->constrained('users')->cascadeOnDelete();
    $table->string('subject');
    $table->enum('status', ['open','closed','awaiting_reply'])->default('open');
    $table->timestamps();
    $table->timestamp('closed_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
