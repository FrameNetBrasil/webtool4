<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\Deixis\CreateObjectData;
use App\Data\Annotation\Deixis\DeleteBBoxData;
use App\Data\Annotation\Deixis\DocumentData;
use App\Data\Annotation\Deixis\ObjectAnnotationData;
use App\Data\Annotation\Deixis\ObjectData;
use App\Data\Annotation\Deixis\ObjectFrameData;
use App\Data\Annotation\Deixis\SearchData;
use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Video;
use App\Services\AnnotationDeixisService;
use App\Services\AnnotationService;
use App\Services\AppService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class DeixisController extends Controller
{
    #[Get(path: '/annotation/deixis')]
    public function browse(SearchData $search)
    {
        $corpus = AnnotationService::browseCorpusBySearch($search, [], "DeixisAnnotation");
        return view("Annotation.Deixis.browse", [
            'data' => $corpus,
        ]);
    }

    #[Post(path: '/annotation/deixis/tree')]
    public function tree(SearchData $search)
    {
        if (!is_null($search->idCorpus) || ($search->document != '')) {
            $data = AnnotationService::browseDocumentBySearch($search, [], "DeixisAnnotation", leaf: true);
        } else {
            $data = AnnotationService::browseCorpusBySearch($search, [], "DeixisAnnotation");
        }
        return view("Annotation.Deixis.browse", [
            'data' => $data
        ])->fragment("tree");
    }


//    #[Get(path: '/annotation/deixis')]
//    public function browse()
//    {
//        $search = session('searchCorpus') ?? SearchData::from();
//        return view("Annotation.Deixis.browse", [
//            'search' => $search
//        ]);
//    }
//
//    #[Post(path: '/annotation/deixis/grid')]
//    public function grid(SearchData $search)
//    {
//        return view("Annotation.Deixis.grid", [
//            'search' => $search
//        ]);
//    }

    private function getData(int $idDocument): array //DocumentData
    {
        $document = Document::byId($idDocument);
        $corpus = Corpus::byId($document->idCorpus);
        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        $timelineData = AnnotationDeixisService::getLayersByDocument($idDocument);
        $timelineConfig = $this->getTimelineConfig($timelineData);
        $groupedLayers = $this->groupLayersByName($timelineData);
        return [
            'idDocument' => $idDocument,
            'document' => $document,
            'corpus' => $corpus,
            'video' => $video,
            'fragment' => 'fe',
            'timeline' => [
                'data' => $timelineData,
                'config' => $timelineConfig,
            ],
            'groupedLayers' => $groupedLayers,
        ];
    }

    #[Get(path: '/annotation/deixis/object/{idDynamicObject}')]
    public function getObject(int $idDynamicObject)
    {
        $object = AnnotationDeixisService::getObject($idDynamicObject ?? 0);
        debug($object);
        return view("Annotation.Deixis.Panes.objectPane", [
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/deixis/fes/{idFrame}')]
    public function feCombobox(int $idFrame)
    {
        debug("=======================");
        return view("Annotation.Deixis.Panes.fes", [
            'idFrame' => $idFrame
        ]);
    }


    #[Get(path: '/annotation/deixis/{idDocument}/{idDynamicObject?}')]
    public function annotation(int|string $idDocument, int $idDynamicObject = null)
    {
        $data = $this->getData($idDocument);
        if (!is_null($idDynamicObject)) {
            $data['idDynamicObject'] = $idDynamicObject;
        }
        return view("Annotation.Deixis.annotation", $data);
    }

    #[Post(path: '/annotation/deixis/createNewObjectAtLayer')]
    public function createNewObjectAtLayer(CreateObjectData $data)
    {
        try {
            return AnnotationDeixisService::createNewObjectAtLayer($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/formAnnotation')]
    public function formAnnotation(ObjectData $data)
    {
        $object = AnnotationDeixisService::getObject($data->idDynamicObject ?? 0);
        return view("Annotation.Deixis.Panes.formPane", [
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/deixis/formAnnotation/{idDynamicObject}')]
    public function getFormAnnotation(int $idDynamicObject)
    {
        $object = AnnotationDeixisService::getObject($idDynamicObject ?? 0);
        return view("Annotation.Deixis.Panes.formAnnotation", [
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/deixis/loadLayerList/{idDocument}')]
    public function loadLayerList(int $idDocument)
    {
        return AnnotationDeixisService::getLayersByDocument($idDocument);
    }

    #[Post(path: '/annotation/deixis/updateObject')]
    public function updateObject(ObjectData $data)
    {
        try {
            $idDynamicObject = AnnotationDeixisService::updateObject($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateObjectRange')]
    public function updateObjectRange(ObjectFrameData $data)
    {
        try {
            debug($data);
            return AnnotationDeixisService::updateObjectFrame($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateObjectFrame')]
    public function updateObjectFrame(ObjectFrameData $data)
    {
        try {
            return AnnotationDeixisService::updateObjectFrame($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        try {
            return AnnotationDeixisService::updateObjectAnnotation($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/deixis/{idDynamicObject}')]
    public function deleteObject(int $idDynamicObject)
    {
        try {
            AnnotationDeixisService::deleteObject($idDynamicObject);
            return $this->renderNotify("success", "Object removed.");
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /*
     * Comment
     */

    #[Get(path: '/annotation/deixis/formComment')]
    public function getFormComment(CommentData $data)
    {
        $object = CommentService::getDynamicObjectComment($data->idDynamicObject);
        return view("Annotation.Deixis.Panes.formComment", [
            'idDocument' => $data->idDocument,
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Post(path: '/annotation/deixis/updateObjectComment')]
    public function updateObjectComment(CommentData $data)
    {
        try {
            debug($data);
            CommentService::updateDynamicObjectComment($data);
            $this->trigger('updateObjectAnnotationEvent');
            return $this->renderNotify("success", "Comment registered.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/deixis/comment/{idDocument}/{idDynamicObject}')]
    public function deleteObjectComment(int $idDocument, int $idDynamicObject)
    {
        try {
            CommentService::deleteDynamicObjectComment($idDocument, $idDynamicObject);
            return $this->renderNotify("success", "Object comment removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/deleteBBox')]
    public function createBBox(DeleteBBoxData $data)
    {
        try {
            debug($data);
            return AnnotationDeixisService::deleteBBoxesFromObject($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /**
     * timeline
     */

//    #[Get(path: '/timeline')]
//    public function index()
//    {
//        $timelineData = $this->getTimelineData();
//        $config = $this->getTimelineConfig($timelineData);
//        $groupedLayers = $this->groupLayersByName($timelineData);
//
//        return view('Annotation.Deixis.Panes.timeline.index', compact('timelineData', 'config', 'groupedLayers'));
//    }

//    #[Post(path: '/timeline/scroll-to-frame')]
//    public function scrollToFrame(Request $request)
//    {
//        $frameNumber = $request->input('frame', 0);
//        $timelineData = $this->getTimelineData();
//        $config = $this->getTimelineConfig($timelineData);
//
//        // Calculate scroll position
//        $framePosition = ($frameNumber - $config['minFrame']) * $config['frameToPixel'];
//        $scrollPosition = max(0, $framePosition - 400 + $config['labelWidth']); // 400px ~ half viewport
//
//        return response()->json([
//            'scrollPosition' => $scrollPosition,
//            'frameNumber' => $frameNumber,
//            'message' => "Scrolled to frame: " . number_format($frameNumber)
//        ]);
//    }

//    #[Post(path: '/timeline/highlight-frame')]
//    public function highlightFrame(Request $request)
//    {
//        $frameNumber = $request->input('frame', 0);
//        $timelineData = $this->getTimelineData();
//        $activeObjects = $this->getActiveObjectsAtFrame($timelineData, $frameNumber);
//
//        return view('Annotation.Deixis.Panes.timeline.highlight', compact('activeObjects', 'frameNumber'));
//    }

//    #[Post(path: '/timeline/object-click')]
//    public function objectClick(Request $request)
//    {
//        $layerIndex = $request->input('layerIndex');
//        $objectIndex = $request->input('objectIndex');
//        $lineIndex = $request->input('lineIndex');
//
//        $timelineData = $this->getTimelineData();
//        $object = $timelineData[$layerIndex]['objects'][$objectIndex] ?? null;
//
//        if (!$object) {
//            return response('Object not found', 404);
//        }
//
//        $clickData = [
//            'layer' => $timelineData[$layerIndex]['layer'],
//            'layerIndex' => $layerIndex,
//            'lineIndex' => $lineIndex,
//            'objectIndex' => $objectIndex,
//            'object' => $object,
//            'frameRange' => $object['startFrame'] . '-' . $object['endFrame'],
//            'duration' => $object['endFrame'] - $object['startFrame']
//        ];
//
//        return view('Annotation.Deixis.Panes.timeline.object-info', compact('clickData', 'object'));
//    }

//    private function getTimelineData()
//    {
//        $data = AnnotationDeixisService::getLayersByDocument(1705);
//        return $data;
//    }

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
            'timelineHeight' => (24 * count($timelineData)) + 10
        ];
    }

    private function groupLayersByName($timelineData): array
    {
        $layerGroups = [];

        foreach ($timelineData as $originalIndex => $layer) {
            $layerName = $layer['layer'];

            if (!isset($layerGroups[$layerName])) {
                $layerGroups[$layerName] = [
                    'name' => $layerName,
                    'lines' => []
                ];
            }

            $layerGroups[$layerName]['lines'][] = array_merge($layer, [
                'originalIndex' => $originalIndex
            ]);
        }

        return array_values($layerGroups);
    }

//    private function getActiveObjectsAtFrame($timelineData, $frameNumber)
//    {
//        $activeObjects = [];
//
//        foreach ($timelineData as $layerIndex => $layer) {
//            foreach ($layer['objects'] as $objectIndex => $object) {
//                if ($frameNumber >= $object->startFrame && $frameNumber <= $object->endFrame) {
//                    $activeObjects[] = [
//                        'layerIndex' => $layerIndex,
//                        'objectIndex' => $objectIndex,
//                        'object' => $object
//                    ];
//                }
//            }
//        }
//
//        return $activeObjects;
//    }


}
