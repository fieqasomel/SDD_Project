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
            $table->string('PU_IC', 20)->change(); // Change from integer to string to handle IC numbers properly
            $table->string('PU_PhoneNum', 20)->change(); // Change from integer to string to handle phone numbers with leading zeros
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publicuser', function (Blueprint $table) {
            $table->integer('PU_IC')->change(); // Revert back to integer
            $table->integer('PU_PhoneNum')->change(); // Revert back to integer
        });
    }
};
