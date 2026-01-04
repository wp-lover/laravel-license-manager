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
        Schema::create('reseller_quotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reseller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('free_license_limit')->default(5);
            $table->unsignedInteger('used_free_licenses')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_quotas');
    }
};
