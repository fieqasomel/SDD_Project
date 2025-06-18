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
            $table->enum('mcmc_status', ['Pending Review', 'Under Review', 'Approved', 'Rejected', 'Forwarded'])
                  ->default('Pending Review')->after('I_Status');
            $table->string('mcmc_processed_by', 7)->nullable()->after('mcmc_status');
            $table->timestamp('mcmc_processed_at')->nullable()->after('mcmc_processed_by');
            $table->string('mcmc_forwarded_by', 7)->nullable()->after('mcmc_processed_at');
            $table->timestamp('mcmc_forwarded_at')->nullable()->after('mcmc_forwarded_by');
            $table->text('mcmc_notes')->nullable()->after('mcmc_forwarded_at');
            $table->text('mcmc_rejection_reason')->nullable()->after('mcmc_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry', function (Blueprint $table) {
            $table->dropColumn([
                'mcmc_status',
                'mcmc_processed_by',
                'mcmc_processed_at',
                'mcmc_forwarded_by',
                'mcmc_forwarded_at',
                'mcmc_notes',
                'mcmc_rejection_reason'
            ]);
        });
    }
};
