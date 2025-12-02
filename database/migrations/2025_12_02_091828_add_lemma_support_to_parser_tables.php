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
        // Add lemma and POS support to parser_node table
        Schema::table('parser_node', function (Blueprint $table) {
            $table->integer('idLemma')->nullable()->after('label');
            $table->string('pos', 20)->nullable()->after('idLemma');

            $table->foreign('idLemma')->references('idLemma')->on('lemma')->onDelete('set null');
            $table->index('idLemma');
        });

        // Add lemma support to parser_grammar_node table
        Schema::table('parser_grammar_node', function (Blueprint $table) {
            $table->integer('idLemma')->nullable()->after('label');

            $table->foreign('idLemma')->references('idLemma')->on('lemma')->onDelete('set null');
            $table->index('idLemma');
        });

        // Add lemmatized components to parser_mwe table
        Schema::table('parser_mwe', function (Blueprint $table) {
            $table->json('componentsLemma')->nullable()->after('components');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parser_node', function (Blueprint $table) {
            $table->dropForeign(['idLemma']);
            $table->dropColumn(['idLemma', 'pos']);
        });

        Schema::table('parser_grammar_node', function (Blueprint $table) {
            $table->dropForeign(['idLemma']);
            $table->dropColumn('idLemma');
        });

        Schema::table('parser_mwe', function (Blueprint $table) {
            $table->dropColumn('componentsLemma');
        });
    }
};
