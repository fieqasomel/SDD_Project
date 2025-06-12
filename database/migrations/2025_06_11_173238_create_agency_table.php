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
        Schema::create('agency', function (Blueprint $table) {
            $table->string('A_ID', 7)->primary();
            $table->string('A_Name', 50)->nullable();
            $table->string('A_userName', 10);
            $table->string('A_Address', 225)->nullable();
            $table->string('A_Email', 50)->nullable();
            $table->integer('A_PhoneNum')->nullable();
            $table->string('A_Category', 50)->nullable();
            $table->string('A_ProfilePicture', 50)->nullable();
            $table->string('A_Password', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency');
    }
};
