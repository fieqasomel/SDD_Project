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
        Schema::create('complaint', function (Blueprint $table) {
            $table->string('C_ID', 7)->primary();
            $table->string('I_ID', 7)->nullable();
            $table->string('A_ID', 7)->nullable();
            $table->string('M_ID', 7)->nullable();
            $table->date('C_AssignedDate')->nullable();
            $table->text('C_Comment')->nullable();
            $table->text('C_History')->nullable();
            
            $table->foreign('I_ID')->references('I_ID')->on('inquiry');
            $table->foreign('A_ID')->references('A_ID')->on('agency');
            $table->foreign('M_ID')->references('M_ID')->on('mcmc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint');
    }
};
