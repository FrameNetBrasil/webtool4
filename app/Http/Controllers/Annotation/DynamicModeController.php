<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Resource\Video\ObjectSearchData;
use App\Data\Resource\Video\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Annotation\BrowseService;
use App\Services\Resource\VideoService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'auth')]
class DynamicModeController extends Controller
{
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
        return VideoService::getResourceData($idDocument, $idDynamicObject, 'dynamicMode');
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

    #[Get(path: '/annotation/dynamicMode/object')]
    public function getObject(ObjectSearchData $data)
    {
        if ($data->idObject == 0) {
            return view('Annotation.DynamicMode.Forms.formNewObject');
        }
        $object = VideoService::getObject($data->idObject ?? 0);
        $object->annotationType = $data->annotationType;
        if (is_null($object)) {
            return $this->renderNotify('error', 'Object not found.');
        }

        return response()
            ->view('Annotation.DynamicMode.Panes.object', [
                'object' => $object,
                'annotationType' => $data->annotationType
            ])->header('HX-Push-Url', "/annotation/dynamicMode/{$object->idDocument}/{$object->idObject}");
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


//    #[Post(path: '/annotation/dynamicMode/updateObjectAnnotation')]
//    public function updateObjectAnnotation(ObjectAnnotationData $data)
//    {
//        try {
//            VideoService::updateObjectAnnotation($data);
//            $object = VideoService::getObject($data->idObject);
//            if (! $object) {
//                return $this->renderNotify('error', 'Object not found after update.');
//            }
//            $this->notify('success', 'Object updated.');
//
//            return $this->render('Annotation.DynamicMode.Panes.timeline.object', [
//                'duration' => $object->endFrame - $object->startFrame,
//                'objectData' => $object,
//            ], 'object');
//        } catch (\Exception $e) {
//            return $this->renderNotify('error', $e->getMessage());
//        }
//    }

//    #[Delete(path: '/annotation/dynamicMode/deleteAllBBoxes/{idDocument}/{idDynamicObject}')]
//    public function deleteAllBBoxes(int $idDocument, int $idDynamicObject)
//    {
//        try {
//            VideoService::deleteBBoxesFromObject($idDynamicObject);
//
//            return $this->redirect("/annotation/dynamicMode/{$idDocument}/{$idDynamicObject}");
//        } catch (\Exception $e) {
//            return $this->renderNotify('error', $e->getMessage());
//        }
//    }

//    #[Delete(path: '/annotation/dynamicMode/{idDocument}/{idDynamicObject}')]
//    public function deleteObject(int $idDocument, int $idDynamicObject)
//    {
//        try {
//            VideoService::deleteObject($idDynamicObject);
//
//            return $this->redirect("/annotation/dynamicMode/{$idDocument}");
//        } catch (\Exception $e) {
//            return $this->renderNotify('error', $e->getMessage());
//        }
//    }



    /*
     * BBox
     */



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

}
