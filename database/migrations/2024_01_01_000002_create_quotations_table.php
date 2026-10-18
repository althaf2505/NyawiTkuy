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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('destination_country', 100)->index();
            $table->string('destination_port', 255)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', [
                'submitted',
                'under_review',
                'quoted',
                'accepted',
                'rejected',
                'cancelled',
                'expired',
            ])->default('submitted')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
