<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\Comment\CommentData;
use App\Data\Annotation\DynamicMode\CloneData;
use App\Data\Annotation\DynamicMode\CreateBBoxData;
use App\Data\Annotation\DynamicMode\CreateObjectData;
use App\Data\Annotation\DynamicMode\GetBBoxData;
use App\Data\Annotation\DynamicMode\ObjectAnnotationData;
use App\Data\Annotation\DynamicMode\ObjectFrameData;
use App\Data\Annotation\DynamicMode\ObjectSearchData;
use App\Data\Annotation\DynamicMode\SearchData;
use App\Data\Annotation\DynamicMode\UpdateBBoxData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Video;
use App\Services\Annotation\BrowseService;
use App\Services\Annotation\DynamicModeService;
use App\Services\AppService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class _DynamicModeController extends Controller
{
    #[Get(path: '/annotation/dynamicMode/script/{folder}')]
    public function jsObjects(string $folder)
    {
        return response()
            ->view("Annotation.DynamicMode.Scripts.{$folder}")
            ->header('Content-type', 'text/javascript')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    #[Get(path: '/annotation/dynamicMode')]
    public function browse(SearchData $search)
    {
        $corpus = BrowseService::browseCorpusBySearch($search, [], 'DynamicAnnotation');

        return view('Annotation.browseDocuments', [
            'page' => "Dynamic Annotation",
            'url' => "/annotation/dynamicMode",
            'taskGroupName' => 'DynamicAnnotation',
            'data' => $corpus,
        ]);
    }

    private function getData(int $idDocument, ?int $idDynamicObject = null): array
    {
        $document = Document::byId($idDocument);
        if (! $document) {
            throw new \Exception("Document with ID {$idDocument} not found.");
        }

        $corpus = Corpus::byId($document->idCorpus);
        if (! $corpus) {
            throw new \Exception("Corpus with ID {$document->idCorpus} not found.");
        }

        $documentVideo = Criteria::table('view_document_video')
            ->where('idDocument', $idDocument)
            ->first();
        if (! $documentVideo) {
            throw new \Exception("Video not found for document ID {$idDocument}.");
        }

        $video = Video::byId($documentVideo->idVideo);
        if (! $video) {
            throw new \Exception("Video with ID {$documentVideo->idVideo} not found.");
        }
        $timelineData = DynamicModeService::getLayersByDocument($idDocument);
        $timelineConfig = $this->getTimelineConfig($timelineData);
        $groupedLayers = $this->groupLayersByName($timelineData);

        return [
            'idDocument' => $idDocument,
            'document' => $document,
            'corpus' => $corpus,
            'video' => $video,
            'fragment' => 'fe',
            'searchResults' => [],
            'timeline' => [
                'data' => $timelineData,
                'config' => $timelineConfig,
            ],
            'groupedLayers' => $groupedLayers,
            'idDynamicObject' => is_null($idDynamicObject) ? 0 : $idDynamicObject,
        ];
    }

    #[Get(path: '/annotation/dynamicMode/object')]
    public function getObject(ObjectSearchData $data)
    {
        if ($data->idDynamicObject == 0) {
            return view('Annotation.DynamicMode.Forms.formNewObject');
        }
        $object = DynamicModeService::getObject($data->idDynamicObject ?? 0);
        if (is_null($object)) {
            return $this->renderNotify('error', 'Object not found.');
        }

        return response()
            ->view('Annotation.DynamicMode.Panes.objectPane', [
                'object' => $object,
            ])->header('HX-Push-Url', "/annotation/dynamicMode/{$object->idDocument}/{$object->idDynamicObject}");
    }

    #[Post(path: '/annotation/dynamicMode/object/search')]
    public function objectSearch(ObjectSearchData $data)
    {
        $searchResults = [];

        if (! empty($data->frame) || ! empty($data->lu) || ! empty($data->searchIdLayerType) || ($data->idDynamicObject > 0)) {
            $idLanguage = AppService::getCurrentIdLanguage();

            $query = Criteria::table('view_annotation_dynamic as ad')
                ->where('ad.idLanguage', 'left', $idLanguage)
                ->where('ad.idDocument', $data->idDocument);

            if (! empty($data->frame)) {
                $query->whereRaw('(ad.frame LIKE ? OR ad.fe LIKE ?)', [
                    $data->frame.'%',
                    $data->frame.'%',
                ]);
            }

            if (! empty($data->lu)) {
                $searchTerm = '%'.$data->lu.'%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('ad.lu', 'like', $searchTerm);
                });
            }

            if ($data->idDynamicObject != 0) {
                $query->where('ad.idDynamicObject', $data->idDynamicObject);
            }

            $searchResults = $query
                ->select(
                    'ad.idDynamicObject',
                    'ad.name',
                    'ad.startFrame',
                    'ad.endFrame',
                    'ad.startTime',
                    'ad.endTime',
                    'ad.lu',
                    'ad.frame',
                    'ad.fe'
                )
                ->orderBy('ad.idDynamicObject')
                ->orderBy('ad.startFrame')
                ->orderBy('ad.endFrame')
                ->all();

            // Format search results for display
            foreach ($searchResults as $object) {
                $object->displayName = '';
                if (! empty($object->lu)) {
                    $object->displayName .= ($object->displayName ? ' | ' : '').$object->lu;
                }
                if (! empty($object->fe)) {
                    $object->displayName .= ($object->displayName ? ' | ' : '').$object->frame.'.'.$object->fe;
                }
                if (empty($object->displayName)) {
                    $object->displayName = 'None';
                }
            }
        }

        return view('Annotation.DynamicMode.Panes.searchPane', [
            'searchResults' => $searchResults,
            'idDocument' => $data->idDocument,
        ])->fragment('search');
    }

    #[Post(path: '/annotation/dynamicMode/createNewObjectAtLayer')]
    public function createNewObjectAtLayer(CreateObjectData $data)
    {
        try {
            $object = DynamicModeService::createNewObjectAtLayer($data);

            return $this->redirect("/annotation/dynamicMode/{$object->idDocument}/{$object->idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateObjectRange')]
    public function updateObjectRange(ObjectFrameData $data)
    {
        try {
            DynamicModeService::updateObjectFrame($data);

            return $this->redirect("/annotation/dynamicMode/{$data->idDocument}/{$data->idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        try {
            DynamicModeService::updateObjectAnnotation($data);
            $object = DynamicModeService::getObject($data->idDynamicObject);
            if (! $object) {
                return $this->renderNotify('error', 'Object not found after update.');
            }
            $this->notify('success', 'Object updated.');

            return $this->render('Annotation.DynamicMode.Panes.timeline.object', [
                'duration' => $object->endFrame - $object->startFrame,
                'objectData' => $object,
            ], 'object');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/dynamicMode/deleteAllBBoxes/{idDocument}/{idDynamicObject}')]
    public function deleteAllBBoxes(int $idDocument, int $idDynamicObject)
    {
        try {
            DynamicModeService::deleteBBoxesFromObject($idDynamicObject);

            return $this->redirect("/annotation/dynamicMode/{$idDocument}/{$idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/dynamicMode/{idDocument}/{idDynamicObject}')]
    public function deleteObject(int $idDocument, int $idDynamicObject)
    {
        try {
            DynamicModeService::deleteObject($idDynamicObject);

            return $this->redirect("/annotation/dynamicMode/{$idDocument}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/cloneObject')]
    public function cloneObject(CloneData $data)
    {
        try {
            $idDynamicObjectClone = DynamicModeService::cloneObject($data);

            return $this->redirect("/annotation/dynamicMode/{$data->idDocument}/{$idDynamicObjectClone}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    /*
     * BBox
     */

    #[Get(path: '/annotation/dynamicMode/getBBox')]
    public function getBBox(GetBBoxData $data)
    {
        try {
            return Criteria::table('view_dynamicobject_boundingbox')
                ->where('idDynamicObject', $data->idDynamicObject)
                ->where('frameNumber', $data->frameNumber)
                ->first();
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/annotation/dynamicMode/getBoxesContainer/{idDynamicObject}')]
    public function getBoxesContainer(int $idDynamicObject)
    {
        try {
            $dynamicObject = Criteria::byId('dynamicObject', 'idDynamicObject', $idDynamicObject);
            if (! $dynamicObject) {
                return $this->renderNotify('error', "Dynamic object with ID {$idDynamicObject} not found.");
            }

            return view('Annotation.DynamicMode.Forms.boxesContainer', [
                'object' => $dynamicObject,
            ]);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/createBBox')]
    public function createBBox(CreateBBoxData $data)
    {
        try {
            return DynamicModeService::createBBox($data);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            $idBoundingBox = DynamicModeService::updateBBox($data);
            $boundingBox = Criteria::byId('boundingbox', 'idBoundingBox', $idBoundingBox);
            if (! $boundingBox) {
                return $this->renderNotify('error', 'Updated bounding box not found.');
            }

            return $boundingBox;
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    /*
     * Comment
     */

    #[Get(path: '/annotation/dynamicMode/formComment')]
    public function getFormComment(CommentData $data)
    {
        $object = CommentService::getDynamicObjectComment($data->idDynamicObject);
        // Note: object can be null for new comments, which is handled by the view

        return view('Annotation.DynamicMode.Panes.formComment', [
            'idDocument' => $data->idDocument,
            'order' => $data->order,
            'object' => $object,
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/updateObjectComment')]
    public function updateObjectComment(CommentData $data)
    {
        try {
            CommentService::updateDynamicObjectComment($data);
            $this->trigger('updateObjectAnnotationEvent');

            return $this->renderNotify('success', 'Comment registered.');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/dynamicMode/comment/{idDocument}/{idDynamicObject}')]
    public function deleteObjectComment(int $idDocument, int $idDynamicObject)
    {
        try {
            CommentService::deleteDynamicObjectComment($idDocument, $idDynamicObject);

            return $this->renderNotify('success', 'Object comment removed.');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    /**
     * timeline
     */
    private function getTimelineConfig($timelineData): array
    {
        $minFrame = PHP_INT_MAX;
        $maxFrame = PHP_INT_MIN;

        foreach ($timelineData as $layer) {
            foreach ($layer['objects'] as $object) {
                $minFrame = min($minFrame, $object->startFrame);
                $maxFrame = max($maxFrame, $object->endFrame);
            }
        }

        // Add padding
        $minFrame = max(0, $minFrame - 100);
        $maxFrame = $maxFrame + 100;

        return [
            'minFrame' => $minFrame,
            'maxFrame' => $maxFrame,
            'frameToPixel' => 1,
            'minObjectWidth' => 16,
            'objectHeight' => 24,
            'labelWidth' => 150,
            'timelineWidth' => ($maxFrame - $minFrame) * 1,
            'timelineHeight' => (24 * count($timelineData)) + 10,
        ];
    }

    private function groupLayersByName($timelineData): array
    {
        $layerGroups = [];

        foreach ($timelineData as $originalIndex => $layer) {
            $layerName = $layer['layer'];

            if (! isset($layerGroups[$layerName])) {
                $layerGroups[$layerName] = [
                    'name' => $layerName,
                    'lines' => [],
                ];
            }

            $layerGroups[$layerName]['lines'][] = array_merge($layer, [
                'originalIndex' => $originalIndex,
            ]);
        }

        return array_values($layerGroups);
    }

    /**
     * Page
     */
    #[Get(path: '/annotation/dynamicMode/{idDocument}/{idDynamicObject?}')]
    public function annotation(int|string $idDocument, ?int $idDynamicObject = null)
    {
        $data = $this->getData($idDocument, $idDynamicObject);

        return response()
            ->view('Annotation.DynamicMode.annotation', $data)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
