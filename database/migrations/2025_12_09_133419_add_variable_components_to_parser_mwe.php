<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds support for variable component patterns in MWE detection.
     * Components can now be:
     * - W (Word): Exact word form match
     * - L (Lemma): Match against token lemma
     * - P (POS): Match against UDPOS tag
     * - C (CE): Match against phrasal CE label
     * - * (Wildcard): Match any token
     */
    public function up(): void
    {
        Schema::table('parser_mwe', function (Blueprint $table) {
            // Format indicator: 'simple' for backward compat, 'extended' for new format
            $table->enum('componentFormat', ['simple', 'extended'])
                ->default('simple')
                ->after('componentsLemma');

            // Position of first fixed word (W type) for anchor-based indexing
            // NULL means pattern has no fixed words (fully variable)
            $table->tinyInteger('anchorPosition')
                ->nullable()
                ->after('componentFormat');

            // Anchor word value (lowercased) for efficient lookup
            // Computed from first W-type component, NULL if no fixed words
            $table->string('anchorWord', 100)
                ->nullable()
                ->after('anchorPosition');

            // Add index for anchor-based lookup
            $table->index(['idGrammarGraph', 'anchorWord'], 'idx_mwe_anchor');
        });

        // Update existing MWEs to set anchor values from firstWord
        DB::statement("
            UPDATE parser_mwe
            SET anchorPosition = 0,
                anchorWord = firstWord
            WHERE componentFormat = 'simple'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parser_mwe', function (Blueprint $table) {
            $table->dropIndex('idx_mwe_anchor');
            $table->dropColumn(['componentFormat', 'anchorPosition', 'anchorWord']);
        });
    }
};
