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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('sku', 100)->unique();
            $table->string('category', 100)->index();
            $table->text('description');
            $table->json('specifications')->nullable();
            $table->string('image', 255)->nullable();
            $table->decimal('indicative_price', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('unit', 50)->default('MT');
            $table->decimal('moq', 10, 2);
            $table->decimal('stock_capacity', 15, 2)->nullable();
            $table->string('origin', 255)->default('Indonesia');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
