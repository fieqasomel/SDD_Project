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
        // First, drop the foreign key constraint
        Schema::table('inquiry', function (Blueprint $table) {
            $table->dropForeign(['PU_ID']);
        });

        // Modify the PU_ID column in publicuser table
        Schema::table('publicuser', function (Blueprint $table) {
            $table->string('PU_ID', 10)->change(); // Increase from 7 to 10 characters
        });

        // Modify the PU_ID column in inquiry table to match
        Schema::table('inquiry', function (Blueprint $table) {
            $table->string('PU_ID', 10)->change(); // Increase from 7 to 10 characters
        });

        // Recreate the foreign key constraint
        Schema::table('inquiry', function (Blueprint $table) {
            $table->foreign('PU_ID')->references('PU_ID')->on('publicuser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('inquiry', function (Blueprint $table) {
            $table->dropForeign(['PU_ID']);
        });

        // Revert the PU_ID column in inquiry table
        Schema::table('inquiry', function (Blueprint $table) {
            $table->string('PU_ID', 7)->change(); // Revert back to 7 characters
        });

        // Revert the PU_ID column in publicuser table
        Schema::table('publicuser', function (Blueprint $table) {
            $table->string('PU_ID', 7)->change(); // Revert back to 7 characters
        });

        // Recreate the foreign key constraint
        Schema::table('inquiry', function (Blueprint $table) {
            $table->foreign('PU_ID')->references('PU_ID')->on('publicuser');
        });
    }
};
