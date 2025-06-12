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
        // Fix PublicUser table
        Schema::table('publicuser', function (Blueprint $table) {
            $table->string('PU_PhoneNum', 20)->change();
            $table->string('PU_IC', 20)->change();
            $table->string('PU_Password', 255)->change();
        });

        // Fix Agency table
        Schema::table('agency', function (Blueprint $table) {
            $table->string('A_PhoneNum', 20)->change();
            $table->string('A_Password', 255)->change();
        });

        // Fix MCMC table
        Schema::table('mcmc', function (Blueprint $table) {
            $table->string('M_PhoneNum', 20)->change();
            $table->string('M_Password', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert PublicUser table
        Schema::table('publicuser', function (Blueprint $table) {
            $table->integer('PU_PhoneNum')->change();
            $table->integer('PU_IC')->change();
            $table->string('PU_Password', 10)->change();
        });

        // Revert Agency table
        Schema::table('agency', function (Blueprint $table) {
            $table->integer('A_PhoneNum')->change();
            $table->string('A_Password', 10)->change();
        });

        // Revert MCMC table
        Schema::table('mcmc', function (Blueprint $table) {
            $table->integer('M_PhoneNum')->change();
            $table->string('M_Password', 10)->change();
        });
    }
};
