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
        Schema::create('progress', function (Blueprint $table) {
            $table->string('P_ID', 7)->primary();
            $table->string('I_ID', 7)->nullable();
            $table->string('A_ID', 7)->nullable();
            $table->string('P_Status', 10)->nullable();
            $table->dateTime('P_Timestamp')->nullable();
            $table->string('P_Notes', 50)->nullable();
            
            $table->foreign('I_ID')->references('I_ID')->on('inquiry');
            $table->foreign('A_ID')->references('A_ID')->on('agency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
