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
        Schema::create('quotation_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->enum('offered_by', ['admin', 'buyer']);
            $table->string('currency', 3)->default('USD');
            $table->decimal('total_amount', 15, 2);
            $table->string('trade_terms', 50)->nullable();
            $table->text('payment_terms')->nullable();
            $table->text('notes')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('status', [
                'sent',
                'countered',
                'accepted',
                'rejected',
                'expired',
                'superseded',
            ])->default('sent')->index();
            $table->timestamps();

            $table->index(['quotation_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_offers');
    }
};
