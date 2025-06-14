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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 7);
            $table->string('action', 50);
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('M_ID')->on('mcmc')->onDelete('cascade');
            $table->index(['user_id', 'action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
