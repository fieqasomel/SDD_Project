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
        // First, let's convert existing single category to JSON format
        DB::statement("UPDATE agency SET A_Category = JSON_ARRAY(A_Category) WHERE A_Category IS NOT NULL");
        
        // Then change the column type to JSON
        Schema::table('agency', function (Blueprint $table) {
            $table->json('A_Category')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON back to single string (take first element)
        DB::statement("UPDATE agency SET A_Category = JSON_UNQUOTE(JSON_EXTRACT(A_Category, '$[0]')) WHERE A_Category IS NOT NULL");
        
        // Change column back to string
        Schema::table('agency', function (Blueprint $table) {
            $table->string('A_Category')->change();
        });
    }
};
