<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jeeps', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 20)->unique();
            $table->string('model_name', 50);
            $table->enum('status', ['READY', 'MAINTENANCE', 'ON_TRIP'])->default('READY');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jeeps');
    }
};
