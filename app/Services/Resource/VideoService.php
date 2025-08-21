<?php

namespace App\Services\Resource;

use App\Data\Resource\Video\CloneData;
use App\Data\Resource\Video\CreateBBoxData;
use App\Data\Resource\Video\CreateObjectData;
use App\Data\Resource\Video\ObjectAnnotationData;
use App\Data\Resource\Video\ObjectFrameData;
use App\Data\Resource\Video\ObjectSearchData;
use App\Data\Resource\Video\UpdateBBoxData;
use App\Database\Criteria;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Task;
use App\Repositories\Timeline;
use App\Repositories\User;
use App\Repositories\Video;
use App\Services\AppService;
use App\Services\CommentService;
use Illuminate\Support\Facades\DB;

class VideoService
{
    public static function getResourceData(int $idDocument, ?int $idObject = null): array
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
        $timelineData = self::getLayersByDocument($idDocument);
        $timelineConfig = self::getTimelineConfig($timelineData);
        $groupedLayers = self::groupLayersByName($timelineData);

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
            'idObject' => is_null($idObject) ? 0 : $idObject,
        ];

    }

    public static function getLayersByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $objects = Criteria::table('view_annotation_dynamic as ad')
            ->leftJoin('view_lu', 'ad.idLu', '=', 'view_lu.idLU')
            ->leftJoin('frameelement as fe', 'ad.idFrameElement', '=', 'fe.idFrameElement')
            ->leftJoin('color', 'fe.idColor', '=', 'color.idColor')
            ->leftJoin('view_frame', 'view_lu.idFrame', '=', 'view_frame.idFrame')
            ->leftJoin('annotationcomment as ac', 'ad.idDynamicObject', '=', 'ac.idDynamicObject')
            ->where('ad.idLanguage', 'left', $idLanguage)
            ->where('ad.idDocument', $idDocument)
            ->where('view_frame.idLanguage', 'left', $idLanguage)
            ->select('ad.idDynamicObject', 'ad.name', 'ad.startFrame', 'ad.endFrame', 'ad.startTime', 'ad.endTime', 'ad.status', 'ad.origin',
                'ad.idAnnotationLU', 'ad.idLU', 'lu', 'view_lu.name as luName', 'view_frame.name as luFrameName', 'idAnnotationFE', 'ad.idFrameElement', 'ad.idFrame', 'ad.frame', 'ad.fe',
                'color.rgbFg', 'color.rgbBg', 'ac.comment as textComment')
            ->orderBy('ad.startFrame')
            ->orderBy('ad.endFrame')
            ->orderBy('ad.idDynamicObject')
            ->keyBy('idDynamicObject')
            ->all();
        $bboxes = [];
        $idDynamicObjectList = array_keys($objects);
        if (count($idDynamicObjectList) > 0) {
            $bboxList = Criteria::table('view_dynamicobject_boundingbox')
                ->whereIN('idDynamicObject', $idDynamicObjectList)
                ->all();
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox->idDynamicObject][] = $bbox;
            }
        }
        $order = 0;
        foreach ($objects as $object) {
            $object->order = ++$order;
            $object->startTime = (int) ($object->startTime * 1000);
            $object->endTime = (int) ($object->endTime * 1000);
            $object->bboxes = $bboxes[$object->idDynamicObject] ?? [];
            $object->name = '';
            $object->bgColor = 'white';
            $object->fgColor = 'black';
            if ($object->lu != '') {
                $object->name .= $object->lu;
            }
            if ($object->fe != '') {
                $object->bgColor = "#{$object->rgbBg}";
                $object->fgColor = "#{$object->rgbFg}";
                $object->name .= ($object->name != '' ? ' | ' : '').$object->frame.'.'.$object->fe;
            }
        }
        $objectsRows = [];
        $objectsRowsEnd = [];
        // Para manter o paralelismo com a Deixis annotation,
        // estou considerando que todos os objetos estão num "layer fictício", com idLayerType = 0 e idLabel (idLayer) = 0
        $idLayerTypeCurrent = -1;
        $idLayerType = 0;
        foreach ($objects as $i => $object) {
            if ($idLayerType != $idLayerTypeCurrent) {
                $idLayerTypeCurrent = $idLayerType;
                $objectsRows[$idLayerType][0][] = $object;
                $objectsRowsEnd[$idLayerType][0] = $object->endFrame;
            } else {
                $allocated = false;
                foreach ($objectsRows[$idLayerType] as $idLayer => $objectRow) {
                    if ($object->startFrame > $objectsRowsEnd[$idLayerType][$idLayer]) {
                        $objectsRows[$idLayerType][$idLayer][] = $object;
                        $objectsRowsEnd[$idLayerType][$idLayer] = $object->endFrame;
                        $allocated = true;
                        break;
                    }
                }
                if (! $allocated) {
                    $idLayer = count($objectsRows[$idLayerType]);
                    $objectsRows[$idLayerType][$idLayer][] = $object;
                    $objectsRowsEnd[$idLayerType][$idLayer] = $object->endFrame;
                }
            }
        }

        $result = [];
        foreach ($objectsRows as $layers) {
            foreach ($layers as $objects) {
                $result[] = [
                    'layer' => 'Single_layer',
                    'objects' => $objects,
                ];
            }
        }

        return $result;
    }

    /**
     * timeline
     */
    private static function getTimelineConfig($timelineData): array
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

    private static function groupLayersByName($timelineData): array
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

    public static function getObject(int $idObject): ?object
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $object = Criteria::table('view_annotation_dynamic as ad')
            ->leftJoin('frameelement as fe', 'ad.idFrameElement', '=', 'fe.idFrameElement')
            ->leftJoin('color', 'fe.idColor', '=', 'color.idColor')
            ->where('ad.idLanguage', 'left', $idLanguage)
            ->where('ad.idDynamicObject', $idObject)
            ->select('ad.idDynamicObject', 'ad.name', 'ad.startFrame', 'ad.endFrame', 'ad.startTime', 'ad.endTime', 'ad.status', 'ad.origin',
                'ad.idAnnotationLU', 'ad.idLU', 'ad.lu', 'ad.idAnnotationFE', 'ad.idFrameElement', 'ad.idFrame', 'ad.frame', 'ad.fe', 'color.rgbBg', 'color.rgbFg', 'ad.idLanguage',
                'ad.idDocument')
            ->selectRaw("'Single_layer' as nameLayerType")
            ->first();
        if (! is_null($object)) {
            $object->comment = CommentService::getDynamicObjectComment($idObject);
            $object->textComment = $object->comment?->comment;
            $object->name = '';
            $object->bgColor = 'white';
            $object->fgColor = 'black';
            if ($object->lu != '') {
                $object->name .= $object->lu;
            }
            if ($object->fe != '') {
                $object->bgColor = "#{$object->rgbBg}";
                $object->fgColor = "#{$object->rgbFg}";
                $object->name .= ($object->name != '' ? ' | ' : '').$object->frame.'.'.$object->fe;
            }
            $countBBoxes = Criteria::table('view_dynamicobject_boundingbox')
                ->where('idDynamicObject', $idObject)
                ->count();
            $object->hasBBoxes = ($countBBoxes > 0);
        }

        return $object;
    }

    public function objectSearch(ObjectSearchData $data)
    {
        $searchResults = [];

        if (! empty($data->frame) || ! empty($data->lu) || ! empty($data->searchIdLayerType) || ($data->idObject > 0)) {
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

            if ($data->idObject != 0) {
                $query->where('ad.idDynamicObject', $data->idObject);
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

        return $searchResults;
    }

    public static function createNewObjectAtLayer(CreateObjectData $data): object
    {
        $idUser = AppService::getCurrentIdUser();
        $do = json_encode([
            'name' => '',
            'startFrame' => $data->startFrame,
            'endFrame' => $data->endFrame,
            'startTime' => ($data->startFrame - 1) * 0.040,
            'endTime' => ($data->endFrame) * 0.040,
            'status' => 0,
            'origin' => 2,
            'idUser' => $idUser,
        ]);
        $idDynamicObject = Criteria::function('dynamicobject_create(?)', [$do]);
        $dynamicObject = Criteria::byId('dynamicobject', 'idDynamicObject', $idDynamicObject);
        $dynamicObject->idDocument = $data->idDocument;
        Criteria::table('dynamicobject')
            ->where('idDynamicObject', $idDynamicObject)
            ->update(['idLayerType' => $data->idLayerType]);
        $documentVideo = Criteria::table('view_document_video')
            ->where('idDocument', $data->idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        // create relation video_dynamicobject
        Criteria::create('video_dynamicobject', [
            'idVideo' => $video->idVideo,
            'idDynamicObject' => $idDynamicObject,
        ]);

        return $dynamicObject;
    }

    public static function updateObjectFrame(ObjectFrameData $data): int
    {
        $object = self::getObject($data->idObject);
        $object->bboxes = Criteria::table('view_dynamicobject_boundingbox')
            ->where('idDynamicObject', $data->idObject)
            ->orderBy('frameNumber')
            ->all();
        debug($object->bboxes);
        if (! empty($object->bboxes)) {
            $frameFirstBBox = $object->bboxes[0]->frameNumber;
            // se o novo startFrame for menor que o atual, remove todas as bboxes
            if ($data->startFrame < $frameFirstBBox) {
                self::deleteBBoxesByObject($data->idObject);
            } else {
                $idUser = AppService::getCurrentIdUser();
                // remove as bboxes em frames menores que o newStartFrame
                $bboxes = Criteria::table('view_dynamicobject_boundingbox')
                    ->where('idDynamicObject', $data->idObject)
                    ->where('frameNumber', '<', $data->startFrame)
                    ->chunkResult('idBoundingBox', 'idBoundingBox');
                foreach ($bboxes as $idBoundingBox) {
                    Criteria::function('boundingbox_dynamic_delete(?,?)', [$idBoundingBox, $idUser]);
                }
                // remove as bboxes em frames maiores que o newEndFrame
                $bboxes = Criteria::table('view_dynamicobject_boundingbox')
                    ->where('idDynamicObject', $data->idObject)
                    ->where('frameNumber', '>', $data->endFrame)
                    ->chunkResult('idBoundingBox', 'idBoundingBox');
                foreach ($bboxes as $idBoundingBox) {
                    Criteria::function('boundingbox_dynamic_delete(?,?)', [$idBoundingBox, $idUser]);
                }
            }
        }
        Criteria::table('dynamicobject')
            ->where('idDynamicObject', $data->idObject)
            ->update([
                'startFrame' => $data->startFrame,
                'endFrame' => $data->endFrame,
                'startTime' => $data->startTime,
                'endTime' => $data->endTime,
            ]);

        return $data->idObject;
    }

    private static function deleteBBoxesByObject(int $idObject)
    {
        $bboxes = Criteria::table('view_dynamicobject_boundingbox as db')
            ->where('db.idDynamicObject', $idObject)
            ->select('db.idBoundingBox')
            ->chunkResult('idBoundingBox', 'idBoundingBox');
        Criteria::table('dynamicobject_boundingbox')
            ->whereIn('idBoundingBox', $bboxes)
            ->delete();
        Criteria::table('boundingbox')
            ->whereIn('idBoundingBox', $bboxes)
            ->delete();
    }

    public static function updateObjectAnnotation(ObjectAnnotationData $data): int
    {
        $usertask = Task::getCurrentUserTask($data->idDocument);
        $do = Criteria::byId('dynamicobject', 'idDynamicObject', $data->idObject);
        Criteria::deleteById('annotation', 'idDynamicObject', $do->idDynamicObject);
        if ($data->idFrameElement) {
            $fe = Criteria::byId('frameelement', 'idFrameElement', $data->idFrameElement);
            $json = json_encode([
                'idEntity' => $fe->idEntity,
                'idDynamicObject' => $do->idDynamicObject,
                'idUserTask' => $usertask->idUserTask,
            ]);
            $idAnnotation = Criteria::function('annotation_create(?)', [$json]);
            Timeline::addTimeline('annotation', $idAnnotation, 'C');
        }
        if ($data->idLU) {
            $lu = Criteria::byId('lu', 'idLU', $data->idLU);
            $json = json_encode([
                'idEntity' => $lu->idEntity,
                'idDynamicObject' => $do->idDynamicObject,
                'idUserTask' => $usertask->idUserTask,
            ]);
            $idAnnotation = Criteria::function('annotation_create(?)', [$json]);
            Timeline::addTimeline('annotation', $idAnnotation, 'C');
        }
        return $data->idObject;
    }

    public static function deleteBBoxesFromObject(int $idDynamicObject): int
    {
        $idUser = AppService::getCurrentIdUser();
        $bboxes = Criteria::table('view_dynamicobject_boundingbox')
            ->where('idDynamicObject', $idDynamicObject)
            ->chunkResult('idBoundingBox', 'idBoundingBox');
        foreach ($bboxes as $idBoundingBox) {
            Criteria::function('boundingbox_dynamic_delete(?,?)', [$idBoundingBox, $idUser]);
        }

        return $idDynamicObject;
    }

    public static function deleteObject(int $idObject): void
    {
        // se pode remover o objeto se for Manager da task ou se for o criador do objeto
        $dynamicObjectAnnotation = Criteria::byId('view_annotation_dynamic', 'idDynamicObject', $idObject);
        $taskManager = Task::getTaskManager($dynamicObjectAnnotation->idDocument);
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        if (! User::isManager($user)) {
            if ($taskManager->idUser != $idUser) {
                $tl = Criteria::table('timeline')
                    ->where('tablename', 'dynamicobject')
                    ->where('id', $idObject)
                    ->select('idUser')
                    ->first();
                if ($tl->idUser != $idUser) {
                    throw new \Exception('Object can not be removed.');
                }
            }
        }
        DB::transaction(function () use ($idObject) {
            self::deleteBBoxesByObject($idObject);
            $idUser = AppService::getCurrentIdUser();
            Criteria::function('dynamicobject_delete(?,?)', [$idObject, $idUser]);
        });
    }

    public static function cloneObject(CloneData $data): int
    {
        $idUser = AppService::getCurrentIdUser();
        $idDynamicObject = $data->idObject;
        $do = self::getObject($idDynamicObject);
        $clone = json_encode([
            'name' => $do->name,
            'startFrame' => (int) $do->startFrame,
            'endFrame' => (int) $do->endFrame,
            'startTime' => (float) $do->startTime,
            'endTime' => (float) $do->endTime,
            'status' => (int) $do->status,
            'origin' => (int) $do->origin,
            'idUser' => $idUser,
        ]);
        $idDynamicObjectClone = Criteria::function('dynamicobject_create(?)', [$clone]);
        $dynamicObjectClone = Criteria::byId('dynamicobject', 'idDynamicObject', $idDynamicObjectClone);
        $documentVideo = Criteria::table('view_document_video')
            ->where('idDocument', $data->idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        // create relation video_dynamicobject
        Criteria::create('video_dynamicobject', [
            'idVideo' => $video->idVideo,
            'idDynamicObject' => $idDynamicObjectClone,
        ]);
        // cloning bboxes
        $bboxes = Criteria::table('view_dynamicobject_boundingbox')
            ->where('idDynamicObject', $idDynamicObject)
            ->all();
        foreach ($bboxes as $bbox) {
            $json = json_encode([
                'frameNumber' => (int) $bbox->frameNumber,
                'frameTime' => (float) $bbox->frameTime,
                'x' => (int) $bbox->x,
                'y' => (int) $bbox->y,
                'width' => (int) $bbox->width,
                'height' => (int) $bbox->height,
                'blocked' => (int) $bbox->blocked,
                'idDynamicObject' => (int) $idDynamicObjectClone,
            ]);
            $idBoundingBox = Criteria::function('boundingbox_dynamic_create(?)', [$json]);
        }

        return $idDynamicObjectClone;
    }

    public static function createBBox(CreateBBoxData $data): int
    {
        $boundingBox = Criteria::table('dynamicobject_boundingbox as dbb')
            ->join('boundingbox as bb', 'dbb.idBoundingBox', '=', 'bb.idBoundingBox')
            ->where('dbb.idDynamicObject', $data->idObject)
            ->where('bb.frameNumber', $data->frameNumber)
            ->first();
        if ($boundingBox) {
            Criteria::function('boundingbox_dynamic_delete(?,?)', [$boundingBox->idBoundingBox, AppService::getCurrentIdUser()]);
        }
        $dynamicObject = Criteria::byId('dynamicobject', 'idDynamicObject', $data->idObject);
        if ($dynamicObject->endFrame < $data->frameNumber) {
            Criteria::table('dynamicobject')
                ->where('idDynamicObject', $data->idObject)
                ->update(['endFrame' => $data->frameNumber]);
        }
        $json = json_encode([
            'frameNumber' => (int) $data->frameNumber,
            'frameTime' => $data->frameNumber * 0.04,
            'x' => (int) $data->bbox['x'],
            'y' => (int) $data->bbox['y'],
            'width' => (int) $data->bbox['width'],
            'height' => (int) $data->bbox['height'],
            'blocked' => (int) $data->bbox['blocked'],
            'idDynamicObject' => (int) $data->idObject,
        ]);
        $idBoundingBox = Criteria::function('boundingbox_dynamic_create(?)', [$json]);

        return $idBoundingBox;
    }

    public static function updateBBox(UpdateBBoxData $data): int
    {
        Criteria::table('boundingbox')
            ->where('idBoundingBox', $data->idBoundingBox)
            ->update($data->bbox);

        return $data->idBoundingBox;
    }

}
