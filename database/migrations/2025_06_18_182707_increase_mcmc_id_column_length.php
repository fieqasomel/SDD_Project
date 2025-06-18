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
        // Drop foreign key constraints first
        DB::statement('ALTER TABLE complaint DROP FOREIGN KEY complaint_m_id_foreign');
        DB::statement('ALTER TABLE report DROP FOREIGN KEY report_m_id_foreign');
        DB::statement('ALTER TABLE inquiry DROP FOREIGN KEY inquiry_mcmc_processed_by_foreign');
        
        // Modify the primary key column
        DB::statement('ALTER TABLE mcmc MODIFY M_ID VARCHAR(8) NOT NULL');
        
        // Modify foreign key columns to match
        DB::statement('ALTER TABLE complaint MODIFY M_ID VARCHAR(8)');
        DB::statement('ALTER TABLE report MODIFY M_ID VARCHAR(8)');
        DB::statement('ALTER TABLE inquiry MODIFY mcmc_processed_by VARCHAR(8)');
        
        // Recreate foreign key constraints
        DB::statement('ALTER TABLE complaint ADD CONSTRAINT complaint_m_id_foreign FOREIGN KEY (M_ID) REFERENCES mcmc(M_ID)');
        DB::statement('ALTER TABLE report ADD CONSTRAINT report_m_id_foreign FOREIGN KEY (M_ID) REFERENCES mcmc(M_ID)');
        DB::statement('ALTER TABLE inquiry ADD CONSTRAINT inquiry_mcmc_processed_by_foreign FOREIGN KEY (mcmc_processed_by) REFERENCES mcmc(M_ID) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcmc', function (Blueprint $table) {
            // Revert M_ID column length back to 7 characters
            $table->string('M_ID', 7)->change();
        });
    }
};
