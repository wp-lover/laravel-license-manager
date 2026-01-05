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
         Schema::create('license_activations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('license_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('identifier'); // domain or app id
            $table->enum('type', ['domain', 'app']);

            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->unique(['license_id', 'identifier']);
            $table->index('license_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_activations');
    }
};
