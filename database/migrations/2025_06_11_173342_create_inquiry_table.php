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
        Schema::create('inquiry', function (Blueprint $table) {
            $table->string('I_ID', 7)->primary();
            $table->string('PU_ID', 7)->nullable();
            $table->string('I_Title', 255)->nullable();
            $table->text('I_Description')->nullable();
            $table->string('I_Category', 50)->nullable();
            $table->date('I_Date')->nullable();
            $table->string('I_Status', 50)->nullable();
            $table->string('I_Source', 255)->nullable();
            $table->string('I_filename', 255)->nullable();
            $table->string('InfoPath', 255)->nullable();
            
            $table->foreign('PU_ID')->references('PU_ID')->on('publicuser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry');
    }
};
