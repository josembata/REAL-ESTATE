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
         Schema::create('ownership_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ownership_id')->constrained('ownerships')->onDelete('cascade');
            $table->string('document_name');      // e.g., Sale Deed, Title
            $table->string('document_type');      // file type (pdf, image)
            $table->string('file_path');          // storage path
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); // admin verification
            $table->date('verification_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ownership_documents');
    }
};
