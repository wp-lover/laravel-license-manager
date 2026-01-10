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
       Schema::table('licenses', function (Blueprint $table) {
        $table->unsignedSmallInteger('max_domains')
              ->default(1)
              ->after('type')
              ->comment('Maximum number of domains/apps allowed for this license');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            //
        });
    }
};
