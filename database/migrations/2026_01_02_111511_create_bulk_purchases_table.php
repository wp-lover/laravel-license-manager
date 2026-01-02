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
        Schema::create('bulk_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reseller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('total_licenses');
            $table->unsignedInteger('remaining_licenses');

            // 0 = free reseller quota
            $table->decimal('price_paid', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_purchases');
    }
};
