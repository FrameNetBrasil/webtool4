<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FrameSemPaiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:frame-sem-pai-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Access frames from view_frame using Criteria builder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $frames = Criteria::table("view_frame")
            ->select("idFrame", "idEntity", "name", "description", "idLanguage")
            ->where("idLanguage", 2)
            ->orderBy("name")
            ->all();

        $this->info("Found " . count($frames) . " frames for language ID 2:");

        $framesWithoutParents = [];

        foreach ($frames as $frame) {
            $this->line("#" . $frame->idFrame . " - " . $frame->name . " (Lang: " . $frame->idLanguage . ")");
            if (!empty($frame->description)) {
                $this->line("  Description: " . substr($frame->description, 0, 100) . (strlen($frame->description) > 100 ? '...' : ''));
            }

            // Check if frame has parent frames
            $parentRelations = Criteria::table("view_frame_relation")
                ->where("f2IdEntity", $frame->idEntity)
                ->whereIn("relationType", ["rel_inheritance", "rel_perspective_on", "rel_subframe"])
                ->select("f1IdEntity", "relationType")
                ->all();

            if (!empty($parentRelations)) {
                $this->line("  Has parent frames:");
                foreach ($parentRelations as $relation) {
                    $this->line("    - Parent ID: " . $relation->f1IdEntity . " (Type: " . $relation->relationType . ")");
                }
            } else {
                $this->line("  No parent frames found");
                // Add to frames without parents array
                $framesWithoutParents[] = [
                    'idFrame' => $frame->idFrame,
                    'idEntity' => $frame->idEntity,
                    'entry' => $frame->name,
                    'definition' => $frame->description ?? ''
                ];
            }
            $this->line("");
        }

        // Create CSV content
        $csvContent = "idFrame,idEntity,entry,definition\n";
        foreach ($framesWithoutParents as $frame) {
            $csvContent .= sprintf(
                "%d,%d,\"%s\",\"%s\"\n",
                $frame['idFrame'],
                $frame['idEntity'],
                str_replace('"', '""', $frame['entry']),
                str_replace('"', '""', $frame['definition'])
            );
        }

        // Save to storage/app/data/frames_without_parents.csv
        Storage::disk('local')->put('data/frames_without_parents.csv', $csvContent);

        $this->info("CSV file created with " . count($framesWithoutParents) . " frames without parents:");
        $this->info("File saved to: storage/app/data/frames_without_parents.csv");
    }
}
