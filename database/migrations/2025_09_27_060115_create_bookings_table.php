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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('agent_id')->nullable();

            
            $table->unsignedBigInteger('unit_price_plan_id')->nullable();
            $table->unsignedBigInteger('room_price_plan_id')->nullable();

            $table->dateTime('check_in');
            $table->dateTime('check_out');

            $table->decimal('total_amount', 14, 2);
            $table->string('currency', 3);

            $table->enum('status', [
                'pending','confirmed','paid','cancelled','checked_in','checked_out','no_show'
            ])->default('pending');

            $table->enum('payment_status', [
                'unpaid','partial','paid','refunded'
            ])->default('unpaid');

            $table->timestamps();
            $table->timestamp('cancelled_at')->nullable();

            // Foreign keys
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');

            //  plan relations
            $table->foreign('unit_price_plan_id')->references('id')->on('unit_price_plans')->onDelete('set null');
            $table->foreign('room_price_plan_id')->references('id')->on('room_price_plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
