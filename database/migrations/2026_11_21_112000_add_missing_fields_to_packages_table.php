<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'duration_text')) {
                $table->string('duration_text', 50)->nullable()->after('duration_minutes');
            }
            if (!Schema::hasColumn('packages', 'destinations_count')) {
                $table->integer('destinations_count')->default(5)->after('duration_text');
            }
            if (!Schema::hasColumn('packages', 'routes_list')) {
                $table->json('routes_list')->nullable()->after('destinations_count');
            }
            if (!Schema::hasColumn('packages', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('routes_list');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $columns = ['gallery_images', 'routes_list', 'destinations_count', 'duration_text'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('packages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
