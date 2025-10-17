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
         Schema::table('leases', function (Blueprint $table) {
        if (!Schema::hasColumn('leases', 'unit_id')) {
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
        }
        if (!Schema::hasColumn('leases', 'booking_id')) {
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            //
        });
    }
};
