<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Resource\Video\CloneData;
use App\Data\Resource\Video\CreateBBoxData;
use App\Data\Resource\Video\CreateObjectData;
use App\Data\Resource\Video\GetBBoxData;
use App\Data\Resource\Video\ObjectAnnotationData;
use App\Data\Resource\Video\ObjectFrameData;
use App\Data\Resource\Video\ObjectSearchData;
use App\Data\Resource\Video\SearchData;
use App\Data\Resource\Video\UpdateBBoxData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Services\Annotation\BrowseService;
use App\Services\Annotation\DynamicModeService;
use App\Services\AppService;
use App\Services\CommentService;
use App\Services\Resource\VideoService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class DynamicModeController extends Controller
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
        return VideoService::getResourceData($idDocument, $idDynamicObject);
    }

    #[Get(path: '/annotation/dynamicMode/object')]
    public function getObject(ObjectSearchData $data)
    {
        if ($data->idObject == 0) {
            return view('Annotation.DynamicMode.Forms.formNewObject');
        }
        $object = VideoService::getObject($data->idObject ?? 0);
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
        $searchResults = VideoService::objectSearch($data);
        return view('Annotation.DynamicMode.Panes.searchPane', [
            'searchResults' => $searchResults,
            'idDocument' => $data->idDocument,
        ])->fragment('search');
    }

    #[Post(path: '/annotation/dynamicMode/createNewObjectAtLayer')]
    public function createNewObjectAtLayer(CreateObjectData $data)
    {
        try {
            $object = VideoService::createNewObjectAtLayer($data);

            return $this->redirect("/annotation/dynamicMode/{$object->idDocument}/{$object->idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateObjectRange')]
    public function updateObjectRange(ObjectFrameData $data)
    {
        try {
            VideoService::updateObjectFrame($data);

            return $this->redirect("/annotation/dynamicMode/{$data->idDocument}/{$data->idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        try {
            VideoService::updateObjectAnnotation($data);
            $object = VideoService::getObject($data->idObject);
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
            VideoService::deleteBBoxesFromObject($idDynamicObject);

            return $this->redirect("/annotation/dynamicMode/{$idDocument}/{$idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/dynamicMode/{idDocument}/{idDynamicObject}')]
    public function deleteObject(int $idDocument, int $idDynamicObject)
    {
        try {
            VideoService::deleteObject($idDynamicObject);

            return $this->redirect("/annotation/dynamicMode/{$idDocument}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/cloneObject')]
    public function cloneObject(CloneData $data)
    {
        try {
            $idDynamicObjectClone = VideoService::cloneObject($data);

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
                ->where('idDynamicObject', $data->idObject)
                ->where('frameNumber', $data->frameNumber)
                ->first();
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

//    #[Get(path: '/annotation/dynamicMode/getBoxesContainer/{idDynamicObject}')]
//    public function getBoxesContainer(int $idDynamicObject)
//    {
//        try {
//            $dynamicObject = Criteria::byId('dynamicObject', 'idDynamicObject', $idDynamicObject);
//            if (! $dynamicObject) {
//                return $this->renderNotify('error', "Dynamic object with ID {$idDynamicObject} not found.");
//            }
//
//            return view('Annotation.DynamicMode.Forms.boxesContainer', [
//                'object' => $dynamicObject,
//            ]);
//        } catch (\Exception $e) {
//            return $this->renderNotify('error', $e->getMessage());
//        }
//    }

    #[Post(path: '/annotation/dynamicMode/createBBox')]
    public function createBBox(CreateBBoxData $data)
    {
        try {
            return VideoService::createBBox($data);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            $idBoundingBox = VideoService::updateBBox($data);
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
//    private function getTimelineConfig($timelineData): array
//    {
//        $minFrame = PHP_INT_MAX;
//        $maxFrame = PHP_INT_MIN;
//
//        foreach ($timelineData as $layer) {
//            foreach ($layer['objects'] as $object) {
//                $minFrame = min($minFrame, $object->startFrame);
//                $maxFrame = max($maxFrame, $object->endFrame);
//            }
//        }
//
//        // Add padding
//        $minFrame = max(0, $minFrame - 100);
//        $maxFrame = $maxFrame + 100;
//
//        return [
//            'minFrame' => $minFrame,
//            'maxFrame' => $maxFrame,
//            'frameToPixel' => 1,
//            'minObjectWidth' => 16,
//            'objectHeight' => 24,
//            'labelWidth' => 150,
//            'timelineWidth' => ($maxFrame - $minFrame) * 1,
//            'timelineHeight' => (24 * count($timelineData)) + 10,
//        ];
//    }
//
//    private function groupLayersByName($timelineData): array
//    {
//        $layerGroups = [];
//
//        foreach ($timelineData as $originalIndex => $layer) {
//            $layerName = $layer['layer'];
//
//            if (! isset($layerGroups[$layerName])) {
//                $layerGroups[$layerName] = [
//                    'name' => $layerName,
//                    'lines' => [],
//                ];
//            }
//
//            $layerGroups[$layerName]['lines'][] = array_merge($layer, [
//                'originalIndex' => $originalIndex,
//            ]);
//        }
//
//        return array_values($layerGroups);
//    }

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
