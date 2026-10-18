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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('shipment_number', 50)->unique();
            $table->string('origin', 255);
            $table->string('destination', 255);
            $table->string('carrier', 255)->nullable();
            $table->string('tracking_reference', 255)->nullable();
            $table->date('estimated_arrival')->nullable();
            $table->enum('status', [
                'preparing',
                'ready_to_ship',
                'in_transit',
                'arrived',
                'delivered',
            ])->default('preparing')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
