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
        Schema::table('parser_link', function (Blueprint $table) {
            // Add stage column to track which parsing stage created this link
            $table->string('stage', 20)->default('translation')->after('weight')
                ->comment('Stage: transcription|translation|folding');

            // Add compatibility score for feature-driven linking
            $table->decimal('compatibilityScore', 5, 3)->nullable()->after('stage')
                ->comment('Feature compatibility score (0-1)');

            // Add feature match details
            $table->json('featureMatch')->nullable()->after('compatibilityScore')
                ->comment('Which features matched/mismatched');

            // Add index for stage-based queries
            $table->index(['idParserGraph', 'stage'], 'idx_link_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parser_link', function (Blueprint $table) {
            $table->dropIndex('idx_link_stage');
            $table->dropColumn(['stage', 'compatibilityScore', 'featureMatch']);
        });
    }
};
