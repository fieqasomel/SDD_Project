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
        Schema::table('inquiry', function (Blueprint $table) {
            $table->string('processed_by', 7)->nullable()->after('InfoPath');
            $table->datetime('processed_date')->nullable()->after('processed_by');
            $table->text('mcmc_notes')->nullable()->after('processed_date');
            $table->enum('priority_level', ['Low', 'Medium', 'High', 'Critical'])->default('Medium')->after('mcmc_notes');
            $table->boolean('is_serious')->default(true)->after('priority_level');
            
            // Add foreign key constraint
            $table->foreign('processed_by')->references('M_ID')->on('mcmc')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['processed_by', 'processed_date', 'mcmc_notes', 'priority_level', 'is_serious']);
        });
    }
};
