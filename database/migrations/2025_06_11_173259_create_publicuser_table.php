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
        Schema::create('publicuser', function (Blueprint $table) {
            $table->string('PU_ID', 7)->primary();
            $table->string('PU_Name', 255)->nullable();
            $table->integer('PU_IC')->nullable();
            $table->integer('PU_Age')->nullable();
            $table->string('PU_Address', 255)->nullable();
            $table->string('PU_Email', 50)->nullable();
            $table->integer('PU_PhoneNum')->nullable();
            $table->string('PU_Gender', 10)->nullable();
            $table->string('PU_Password', 10)->nullable();
            $table->string('PU_ProfilePicture', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicuser');
    }
};
