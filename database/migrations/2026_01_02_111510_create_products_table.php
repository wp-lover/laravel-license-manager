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
        // 2. products (your version + improvements)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['wp_plugin', 'wp_theme', 'flutter_app']);
            $table->text('description')->nullable();
            $table->string('current_version')->default('1.0.0');
            $table->string('update_url')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->boolean('supports_trial')->default(false);
            $table->unsignedSmallInteger('trial_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_active']);
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
