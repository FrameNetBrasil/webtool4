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
        Schema::table('parser_node', function (Blueprint $table) {
            // Add features JSON column for UD morphological features
            $table->json('features')->nullable()->after('idMWE')
                ->comment('UD morphological features from TrankitService');

            // Add stage column to track which parsing stage created this node
            $table->string('stage', 20)->default('transcription')->after('features')
                ->comment('Stage: transcription|translation|folding');

            // Add index for stage-based queries
            $table->index(['idParserGraph', 'stage'], 'idx_node_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parser_node', function (Blueprint $table) {
            $table->dropIndex('idx_node_stage');
            $table->dropColumn(['features', 'stage']);
        });
    }
};
