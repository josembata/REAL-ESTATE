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
        Schema::table('properties', function (Blueprint $table) {
             //add title deed number to properties table after name column
            $table->string('title_deed_number')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void 
    {
        Schema::table('properties', function (Blueprint $table) {
           
        });
    }
};
