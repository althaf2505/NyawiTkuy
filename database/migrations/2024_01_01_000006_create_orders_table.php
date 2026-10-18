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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_offer_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('trade_terms', 50)->nullable();
            $table->text('payment_terms')->nullable();
            $table->enum('status', [
                'awaiting_payment',
                'processing',
                'shipped',
                'completed',
                'cancelled',
            ])->default('awaiting_payment')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
