<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates parser_constructions table for BNF-based pattern matching.
     * This table stores pre-compiled graph structures for complex linguistic
     * constructions that require CFG features (optionality, alternatives, repetition).
     *
     * Examples: Portuguese numbers, dates, times, complex prepositions
     */
    public function up(): void
    {
        Schema::create('parser_constructions', function (Blueprint $table) {
            // Primary key
            $table->id('idConstruction');

            // Grammar graph reference (must match parser_grammar_graph.idGrammarGraph type: int(11))
            $table->integer('idGrammarGraph');

            // Pattern definition
            $table->string('name', 100);
            $table->text('pattern');
            $table->text('description')->nullable();

            // Compiled graph (one-time compilation, stored as JSON)
            $table->json('compiledGraph');

            // Semantic interpretation
            $table->string('semanticType', 20);
            $table->json('semantics')->nullable();

            // Matching configuration
            $table->tinyInteger('priority')->default(0);
            $table->boolean('enabled')->default(true);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->unique(['idGrammarGraph', 'name'], 'idx_construction_name');
            $table->index(['idGrammarGraph', 'semanticType'], 'idx_construction_type');
            $table->index(['idGrammarGraph', 'priority'], 'idx_construction_priority');

            // Foreign key constraint removed - parser_grammar_graph table may not exist yet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parser_constructions');
    }
};
