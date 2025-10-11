<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->string('lease_number')->unique();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // tenant
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null'); // property owner
            $table->string('file_path')->nullable(); // where PDF saved (storage)
            $table->timestamp('signed_at')->nullable(); // when signed by both parties
            $table->enum('status', ['draft','generated','signed','cancelled'])->default('generated');
           $table->date('term_start')->nullable();
           $table->date('term_end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
