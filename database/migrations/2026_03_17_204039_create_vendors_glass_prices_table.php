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
        Schema::create('vendors_glass_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('glass_type_id')->constrained('glass_types')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->integer('warranty_time');
            $table->integer('delivery_time');
            $table->timestamps();

            $table->unique(['vendor_id', 'glass_type_id']);

            $table->index(['vendor_id', 'glass_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors_glass_prices');
    }
};
