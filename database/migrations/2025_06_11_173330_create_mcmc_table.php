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
        Schema::create('mcmc', function (Blueprint $table) {
            $table->string('M_ID', 7)->primary();
            $table->string('M_Name', 50)->nullable();
            $table->string('M_userName', 10);
            $table->string('M_Address', 225)->nullable();
            $table->string('M_Email', 50)->nullable();
            $table->integer('M_PhoneNum')->nullable();
            $table->string('M_Position', 50)->nullable();
            $table->string('M_Password', 10)->nullable();
            $table->string('M_ProfilePicture', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcmc');
    }
};
