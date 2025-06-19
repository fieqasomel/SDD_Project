<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Modify the agency table
        Schema::table('agency', function (Blueprint $table) {
            $table->string('A_ID', 10)->change(); // Increase from 7 to 10 characters
            $table->string('A_Password', 255)->change(); // Also fix password length for hashed passwords
        });
        
        // Modify the referencing tables to match
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('A_ID', 10)->change();
        });
        
        Schema::table('progress', function (Blueprint $table) {
            $table->string('A_ID', 10)->change();
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Revert the changes
        Schema::table('agency', function (Blueprint $table) {
            $table->string('A_ID', 7)->change();
            $table->string('A_Password', 10)->change();
        });
        
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('A_ID', 7)->change();
        });
        
        Schema::table('progress', function (Blueprint $table) {
            $table->string('A_ID', 7)->change();
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};