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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'admin_fee')) {
                $table->decimal('admin_fee', 12, 2)->default(0)->after('gross_amount');
            }
            if (!Schema::hasColumn('payments', 'base_amount')) {
                $table->decimal('base_amount', 12, 2)->nullable()->after('gross_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('payments', 'admin_fee')) {
                $cols[] = 'admin_fee';
            }
            if (Schema::hasColumn('payments', 'base_amount')) {
                $cols[] = 'base_amount';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

