<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description');
            $table->decimal('price_per_jeep', 12, 2);
            $table->integer('duration_minutes');
            $table->string('duration_text', 50)->nullable();
            $table->integer('destinations_count')->default(5);
            $table->json('routes_list')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('category_badge', 50)->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
