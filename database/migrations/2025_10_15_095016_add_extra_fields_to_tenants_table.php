<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Add new fields
            $table->string('id_type')->nullable()->after('user_id');
            $table->string('id_number')->nullable()->after('id_type');
            $table->string('id_picture')->nullable()->after('id_number');
            $table->string('home_address')->nullable()->after('id_picture');
            $table->string('professional')->nullable()->after('home_address');
            $table->string('work_address')->nullable()->after('professional');
            $table->string('emergency_person_name')->nullable()->after('work_address');
            $table->string('emergency_person_contact')->nullable()->after('emergency_person_name');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'id_type',
                'id_number',
                'id_picture',
                'home_address',
                'professional',
                'work_address',
                'emergency_person_name',
                'emergency_person_contact',
            ]);
        });
    }
};
