<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            if (!Schema::hasColumn('leases', 'renewal_amount')) {
                $table->decimal('renewal_amount', 10, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('leases', 'previous_term_end')) {
                $table->date('previous_term_end')->nullable()->after('term_end');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn(['renewal_amount', 'previous_term_end']);
        });
    }
};
