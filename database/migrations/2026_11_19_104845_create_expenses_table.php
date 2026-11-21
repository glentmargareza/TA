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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('expense_number', 50)->nullable()->unique();
            $table->string('name', 255);
            $table->string('category', 100);
            $table->decimal('amount', 14, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('receipt_path', 500)->nullable();
            $table->string('receipt_original_name', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('date');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
