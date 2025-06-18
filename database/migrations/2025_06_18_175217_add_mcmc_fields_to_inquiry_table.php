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
            // Add MCMC processing fields
            $table->text('mcmc_notes')->nullable()->after('InfoPath');
            $table->string('mcmc_processed_by', 7)->nullable()->after('mcmc_notes');
            $table->timestamp('mcmc_processed_at')->nullable()->after('mcmc_processed_by');
            $table->string('rejection_reason', 500)->nullable()->after('mcmc_processed_at');
            
            // Add foreign key constraint
            $table->foreign('mcmc_processed_by')->references('M_ID')->on('mcmc')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['mcmc_processed_by']);
            
            // Drop columns
            $table->dropColumn([
                'mcmc_notes',
                'mcmc_processed_by', 
                'mcmc_processed_at',
                'rejection_reason'
            ]);
        });
    }
};
