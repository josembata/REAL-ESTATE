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
       Schema::create('owners', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->enum('type', ['individual', 'company'])->default('individual');
        $table->string('phone', 50)->nullable();
        $table->string('email', 150)->nullable();
        $table->text('address')->nullable();
        $table->string('national_id', 100)->nullable(); // or company reg. number
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
