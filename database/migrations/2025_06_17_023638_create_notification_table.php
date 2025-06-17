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
        Schema::create('notification', function (Blueprint $table) {
            $table->string('N_ID', 7)->primary();
            $table->string('P_ID', 7)->nullable();
            $table->text('N_Message')->nullable();
            $table->dateTime('N_Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('N_Status', 10)->default('UNREAD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
