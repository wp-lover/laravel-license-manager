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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();               // BIGINT UNSIGNED
            $table->string('license_key_hash')->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reseller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'active', 'expired', 'suspended'])->default('pending');
            $table->unsignedInteger('max_activations')->default(1);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
