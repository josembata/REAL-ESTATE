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
        Schema::table('ownership_documents', function (Blueprint $table) {
            $table->enum('status', ['verified', 'rejected', 'pending'])->default('pending');
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ownership_documents', function (Blueprint $table) {
            //
        });
    }
};
