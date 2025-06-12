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
        Schema::create('report', function (Blueprint $table) {
            $table->string('R_ID', 7)->primary();
            $table->string('A_ID', 7)->nullable();
            $table->string('M_ID', 7)->nullable();
            $table->string('I_ID', 7)->nullable();
            $table->string('P_ID', 7)->nullable();
            $table->string('C_ID', 7)->nullable();
            $table->string('R_title', 50)->nullable();
            $table->dateTime('R_date')->nullable();
            $table->dateTime('R_timeStamp')->nullable();
            $table->string('R_agency', 50)->nullable();
            $table->string('R_category', 50)->nullable();
            $table->string('R_format', 50)->nullable();
            
            $table->foreign('A_ID')->references('A_ID')->on('agency');
            $table->foreign('M_ID')->references('M_ID')->on('mcmc');
            $table->foreign('I_ID')->references('I_ID')->on('inquiry');
            $table->foreign('P_ID')->references('P_ID')->on('progress');
            $table->foreign('C_ID')->references('C_ID')->on('complaint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
