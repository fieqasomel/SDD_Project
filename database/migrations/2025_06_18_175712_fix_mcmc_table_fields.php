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
        Schema::table('mcmc', function (Blueprint $table) {
            // Fix password field length for hashed passwords
            $table->string('M_Password', 255)->change();
            
            // Fix phone number field type
            $table->string('M_PhoneNum', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcmc', function (Blueprint $table) {
            // Revert changes
            $table->string('M_Password', 10)->change();
            $table->integer('M_PhoneNum')->change();
        });
    }
};
