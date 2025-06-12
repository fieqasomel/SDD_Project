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
        Schema::table('complaint', function (Blueprint $table) {
            $table->enum('C_VerificationStatus', ['Pending', 'Accepted', 'Rejected'])->default('Pending')->after('C_Comment');
            $table->text('C_RejectionReason')->nullable()->after('C_VerificationStatus');
            $table->datetime('C_VerificationDate')->nullable()->after('C_RejectionReason');
            $table->string('C_VerifiedBy', 10)->nullable()->after('C_VerificationDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->dropColumn(['C_VerificationStatus', 'C_RejectionReason', 'C_VerificationDate', 'C_VerifiedBy']);
        });
    }
};
