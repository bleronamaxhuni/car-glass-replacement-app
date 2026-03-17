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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('glass_type_id')->constrained('glass_types')->cascadeOnDelete();
            $table->foreignId('vendor_glass_price_id')->constrained('vendors_glass_prices')->cascadeOnDelete();
            $table->decimal('final_price', 10, 2);
            $table->timestamp('requested_at')->useCurrent();

            $table->timestamps();

            $table->index(['car_id','glass_type_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
