<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Update semanticType enum in parser_mwe to use Croft Phrasal CE labels.
 *
 * Migration from v1 (E/V/A/F) to v2 (Phrasal CEs):
 * - E (Entity) → Head (noun-like MWEs like "café da manhã")
 * - V (Eventive) → Head (verb-like MWEs)
 * - A (Attribute) → Mod (modifier-like MWEs)
 * - F (Function) → Adp/Lnk/etc. (function word MWEs)
 * - R (Relational) → kept for backward compatibility
 *
 * New values include all Phrasal CE labels from Croft's flat syntax.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Expand the enum to include both old values (for backward compatibility)
        // and new Phrasal CE values from Croft's flat syntax
        DB::statement("ALTER TABLE parser_mwe MODIFY COLUMN semanticType ENUM(
            'E', 'V', 'A', 'F', 'R',
            'Head', 'Mod', 'Adm', 'Adp', 'Lnk', 'Clf', 'Idx', 'Conj'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (will fail if new values are in use)
        DB::statement("ALTER TABLE parser_mwe MODIFY COLUMN semanticType ENUM('E', 'R', 'A', 'F') NOT NULL");
    }
};
