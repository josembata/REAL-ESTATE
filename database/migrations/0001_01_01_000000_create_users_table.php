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
          Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->string('name', 150);
        $table->string('email', 150)->unique();
        $table->string('password');
        $table->string('phone', 30)->nullable();
        $table->string('avatar', 255)->nullable();
        $table->string('gender', 255)->nullable();
        $table->text('bio')->nullable();
        $table->enum('status', ['active', 'blocked', 'pending'])->default('active');
         $table->boolean('profile_complete')->default(false); 
            $table->timestamp('profile_completed_at')->nullable(); 
        $table->timestamps();
        $table->softDeletes();
    });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
