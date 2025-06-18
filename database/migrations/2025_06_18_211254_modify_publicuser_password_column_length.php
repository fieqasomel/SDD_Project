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
        Schema::table('publicuser', function (Blueprint $table) {
            $table->string('PU_Password', 255)->change(); // Increase from 10 to 255 characters for hashed passwords
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publicuser', function (Blueprint $table) {
            $table->string('PU_Password', 10)->change(); // Revert back to 10 characters
        });
    }
};
