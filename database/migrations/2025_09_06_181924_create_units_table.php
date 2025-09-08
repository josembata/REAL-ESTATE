<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            $table->string('unit_name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('TZS');

            $table->enum('unit_type', ['single','double','suite','office']);
            $table->enum('furnishing', ['unfurnished','partially_furnished','furnished'])->default('unfurnished');
            $table->enum('status', ['available','booked','unavailable'])->default('unavailable');

            $table->integer('size_sqft')->nullable();
            $table->boolean('furnished')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
