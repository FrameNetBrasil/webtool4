<?php

namespace App\Http\Controllers\Frame;

use App\Data\Frame\CreateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Services\AppService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class ResourceController extends Controller
{
    #[Get(path: '/frame/new')]
    public function new()
    {
        return view('Frame.new');
    }

    #[Post(path: '/frame')]
    public function store(CreateData $data)
    {
        try {
            $idFrame = Criteria::function('frame_create(?)', [$data->toJson()]);

            return $this->clientRedirect("/frame/{$idFrame}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/frame/{idFrame}')]
    public function delete(string $idFrame)
    {
        try {
            Criteria::function('frame_delete(?, ?)', [
                $idFrame,
                AppService::getCurrentIdUser(),
            ]);

            return $this->clientRedirect('/frame');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}')]
    public function get(string $id)
    {
        return view('Frame.edit', [
            'frame' => Frame::byId($id),
            'classification' => Frame::getClassificationLabels($id),
        ]);
    }

    #[Get(path: '/frame/nextFrom/{id}')]
    public function nextFrom(string $id)
    {
        $current = Frame::byId($id);
        $next = Criteria::table('view_frame')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->where('name', '>', $current->name)
            ->orderBy('name')
            ->first();

        return $this->clientRedirect("/frame/{$next->idFrame}");
    }

    #[Get(path: '/frame/clone/{id}')]
    public function clone(string $id)
    {
        try {
            $user = AppService::getCurrentUser();
            $idUser = $user ? $user->idUser : 0;

            // 1. Get original frame
            $originalFrame = Frame::byId($id);
            if (! $originalFrame) {
                return $this->renderNotify('error', 'Frame not found');
            }

            // 2. Create cloned frame with _cloned suffix
            $clonedName = $originalFrame->name.'_cloned';
            $frameData = json_encode([
                'nameEn' => $clonedName,
                'idNamespace' => $originalFrame->idNamespace ?? 1,
                'idUser' => $idUser,
            ]);
            $newFrameId = Criteria::function('frame_create(?)', [$frameData]);

            // Get the new frame to access its entity ID
            $newFrame = Frame::byId($newFrameId);

            // 3. Clone all frame elements with ID mapping
            $feEntityMapping = []; // old FE entity ID => new FE entity ID
            $feIdMapping = []; // old FE idFrameElement => old FE entity ID
            $frameElements = Criteria::table('view_frameelement')
                ->where('idFrame', $id)
                ->where('idLanguage', AppService::getCurrentIdLanguage())
                ->all();

            foreach ($frameElements as $fe) {
                // Store old FE mapping for later use
                $feIdMapping[$fe->idFrameElement] = $fe->idEntity;

                // Call fe_create for each frame element
                $newFeId = Criteria::function('fe_create(?, ?, ?, ?, ?)', [
                    $newFrameId,
                    $fe->name,
                    $fe->coreType,
                    $fe->idColor,
                    $idUser,
                ]);

                // Get the new FE's entity ID
                $newFe = Criteria::table('view_frameelement')
                    ->where('idFrameElement', $newFeId)
                    ->where('idLanguage', AppService::getCurrentIdLanguage())
                    ->first();

                // Map old entity ID to new entity ID
                $feEntityMapping[$fe->idEntity] = $newFe->idEntity;
            }

            // 4. Clone FE internal relations (coreset, excludes, requires)
            $feInternalRelations = RelationService::listRelationsFEInternal($id);
            foreach ($feInternalRelations as $relation) {
                // Use the mapping to get entity IDs
                $oldEntity1 = $feIdMapping[$relation->feIdFrameElement] ?? null;
                $oldEntity2 = $feIdMapping[$relation->relatedFEIdFrameElement] ?? null;

                $newEntity1 = $oldEntity1 ? ($feEntityMapping[$oldEntity1] ?? null) : null;
                $newEntity2 = $oldEntity2 ? ($feEntityMapping[$oldEntity2] ?? null) : null;

                if ($newEntity1 && $newEntity2) {
                    RelationService::create(
                        $relation->relationType,
                        $newEntity1,
                        $newEntity2
                    );
                }
            }

            // 5. Clone frame-to-frame relations
            $frameRelationMapping = []; // old idEntityRelation => new idEntityRelation
            $frameRelations = Criteria::table('view_frame_relation')
                ->where('f1IdFrame', $id)
                ->where('idLanguage', AppService::getCurrentIdLanguage())
                ->all();

            foreach ($frameRelations as $relation) {
                // Clone direct relations (original frame was entity1)
                $newRelationId = RelationService::create(
                    $relation->relationType,
                    $newFrame->idEntity,
                    $relation->f2IdEntity
                );
                $frameRelationMapping[$relation->idEntityRelation] = $newRelationId;
            }

            // Clone inverse relations (original frame was entity2)
            $inverseRelations = Criteria::table('view_frame_relation')
                ->where('f2IdFrame', $id)
                ->where('idLanguage', AppService::getCurrentIdLanguage())
                ->all();

            foreach ($inverseRelations as $relation) {
                $newRelationId = RelationService::create(
                    $relation->relationType,
                    $relation->f1IdEntity,
                    $newFrame->idEntity
                );
                $frameRelationMapping[$relation->idEntityRelation] = $newRelationId;
            }

            // 5b. Clone FE-FE relations between frames
            foreach ($frameRelationMapping as $oldRelationId => $newRelationId) {
                $feRelations = Criteria::table('view_fe_relation')
                    ->where('idRelation', $oldRelationId)
                    ->where('idLanguage', AppService::getCurrentIdLanguage())
                    ->all();

                foreach ($feRelations as $feRelation) {
                    // Determine which FE belongs to the cloned frame and map it
                    $newFe1Entity = $feRelation->fe1IdFrame == $id
                        ? ($feEntityMapping[$feRelation->fe1IdEntity] ?? null)
                        : $feRelation->fe1IdEntity;

                    $newFe2Entity = $feRelation->fe2IdFrame == $id
                        ? ($feEntityMapping[$feRelation->fe2IdEntity] ?? null)
                        : $feRelation->fe2IdEntity;

                    if ($newFe1Entity && $newFe2Entity) {
                        RelationService::create(
                            $feRelation->relationType,
                            $newFe1Entity,
                            $newFe2Entity,
                            null,
                            $newRelationId
                        );
                    }
                }
            }

            // 6. Clone frame classifications
            $classifications = Frame::getClassification($id);
            foreach ($classifications as $relationType => $items) {
                foreach ($items as $item) {
                    RelationService::create(
                        $relationType,
                        $newFrame->idEntity,
                        $item->idEntity
                    );
                }
            }

            // 7. Copy multilingual entries for frame
            $frameEntries = Criteria::table('entry')
                ->where('idEntity', $originalFrame->idEntity)
                ->all();

            foreach ($frameEntries as $entry) {
                Criteria::table('entry')->insert([
                    'entry' => $newFrame->entry,
                    'name' => $entry->name,
                    'description' => $entry->description,
                    'nick' => $entry->nick,
                    'idLanguage' => $entry->idLanguage,
                    'idEntity' => $newFrame->idEntity,
                ]);
            }

            // Copy multilingual entries for frame elements
            foreach ($frameElements as $oldFe) {
                $newFeEntity = $feEntityMapping[$oldFe->idEntity] ?? null;
                if ($newFeEntity) {
                    $feEntries = Criteria::table('entry')
                        ->where('idEntity', $oldFe->idEntity)
                        ->all();

                    $newFe = Criteria::table('frameelement')
                        ->where('idEntity', $newFeEntity)
                        ->first();

                    foreach ($feEntries as $entry) {
                        Criteria::table('entry')->insert([
                            'entry' => $newFe->entry,
                            'name' => $entry->name,
                            'description' => $entry->description,
                            'nick' => $entry->nick,
                            'idLanguage' => $entry->idLanguage,
                            'idEntity' => $newFeEntity,
                        ]);
                    }
                }
            }

            // 8. Redirect to cloned frame with success message
            return $this->clientRedirect("/frame/{$newFrameId}");

        } catch (\Exception $e) {
            return $this->renderNotify('error', 'Error cloning frame: '.$e->getMessage());
        }
    }
}
