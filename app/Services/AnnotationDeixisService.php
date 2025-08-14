<?php

namespace App\Services;

use App\Data\Annotation\Deixis\CreateObjectData;
use App\Data\Annotation\Deixis\DeleteBBoxData;
use App\Data\Annotation\Deixis\ObjectAnnotationData;
use App\Data\Annotation\Deixis\ObjectFrameData;
use App\Database\Criteria;
use App\Repositories\Timeline;
use App\Repositories\User;
use App\Repositories\Video;
use Illuminate\Support\Facades\DB;


class AnnotationDeixisService
{
    private static function getCurrentUserTask(int $idDocument): object|null
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        // get usertask for this document
        $usertask = Criteria::table("usertask_document")
            ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
            ->where("usertask_document.idDocument", $idDocument)
            ->where("ut.idUser", $idUser)
            ->select("ut.idUserTask", "ut.idTask")
            ->first();
        if (empty($usertask)) { // usa a task -> dataset -> corpus -> document
            if (User::isManager($user) || User::isMemberOf($user, 'MASTER')) {
                $usertask = (object)[
                    "idUserTask" => 1
                ];
//                $usertask = Criteria::table("usertask_document")
//                    ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
//                    ->where("usertask_document.idDocument", $idDocument)
//                    ->where("ut.idUser", -2)
//                    ->select("ut.idUserTask", "ut.idTask")
//                    ->first();
            } else {
                $usertask = null;
            }
        }
        return $usertask;
    }

    private static function deleteBBoxesByDynamicObject(int $idDynamicObject)
    {
        $bboxes = Criteria::table("view_dynamicobject_boundingbox as db")
            ->join("boundingbox as bb", "db.idBoundingBox", "=", "bb.idBoundingBox")
            ->where("db.idDynamicObject", $idDynamicObject)
            ->select("bb.idBoundingBox", "bb.idAnnotationObject")
            ->keyBy("idBoundingBox")
            ->all();

        foreach ($bboxes as $bbox) {
            // Remove the boundingbox using the stored function which handles relationships
            Criteria::function("boundingbox_dynamic_delete(?,?)", [$bbox->idBoundingBox, AppService::getCurrentIdUser()]);
        }
    }

    public static function createNewObjectAtLayer(CreateObjectData $data): object
    {
        $idUser = AppService::getCurrentIdUser();
        $do = json_encode([
            'name' => "",
            'startFrame' => $data->startFrame,
            'endFrame' => $data->endFrame,
            'startTime' => ($data->startFrame - 1) * 0.040,
            'endTime' => ($data->endFrame) * 0.040,
            'status' => 0,
            'origin' => 5,
            'idUser' => $idUser
        ]);
        $idDynamicObject = Criteria::function("dynamicobject_create(?)", [$do]);
        $dynamicObject = Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        Criteria::table("dynamicobject")
            ->where("idDynamicObject", $idDynamicObject)
            ->update(['idLayerType' => $data->idLayerType]);
        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $data->idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        Criteria::create("video_dynamicobject",[
            "idVideo" => $video->idVideo,
            "idDynamicObject" => $dynamicObject->idDynamicObject,
        ]);
        return $dynamicObject;
    }

    public static function getObject(int $idDynamicObject): object|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $do = Criteria::table("view_annotation_deixis")
            ->where("idLanguageLT", "left", $idLanguage)
            ->where("idDynamicObject", $idDynamicObject)
            ->select("idDynamicObject", "name", "startFrame", "endFrame", "startTime", "endTime", "status", "origin", "idLayerType", "nameLayerType", "idLanguageLT",
                "idAnnotationLU", "idLU", "lu", "idAnnotationFE", "idFrameElement", "idFrame", "frame", "fe", "colorFE", "idLanguageFE",
                "idAnnotationGL", "idGenericLabel", "gl", "bgColorGL", "fgColorGL","idLanguageGL", "layerGroup", "idDocument")
            ->first();
        return $do;
    }

    public static function getLayersByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $objects = Criteria::table("view_annotation_deixis as ad")
            ->leftJoin("view_lu", "ad.idLu", "=", "view_lu.idLU")
            ->leftJoin("view_frame", "view_lu.idFrame", "=", "view_frame.idFrame")
            ->leftJoin("annotationcomment as ac", "ad.idDynamicObject", "=", "ac.idDynamicObject")
            ->where("ad.idLanguageFE", "left", $idLanguage)
            ->where("ad.idLanguageLT", "=", $idLanguage)
            ->where("ad.idDocument", $idDocument)
            ->where("view_frame.idLanguage", "left", $idLanguage)
            ->select("ad.idDynamicObject", "ad.name", "ad.startFrame", "ad.endFrame", "ad.startTime", "ad.endTime", "ad.status", "ad.origin", "ad.idLayerType", "ad.nameLayerType",
                "ad.idAnnotationLU", "ad.idLU", "lu", "view_lu.name as luName", "view_frame.name as luFrameName", "idAnnotationFE", "idFrameElement", "ad.idFrame", "ad.frame", "ad.fe", "ad.colorFE",
                "ad.idAnnotationGL", "ad.idGenericLabel", "ad.gl", "ad.bgColorGL", "ad.fgColorGL","ad.layerGroup", "ac.comment")
            ->orderBy("ad.nameLayerType")
            ->orderBy("ad.startFrame")
            ->orderBy("ad.endFrame")
            ->orderBy("ad.idDynamicObject")
            ->keyBy("idDynamicObject")
            ->all();
        $bboxes = [];
        $idDynamicObjectList = array_keys($objects);
        if (count($idDynamicObjectList) > 0) {
            $bboxList = Criteria::table("view_dynamicobject_boundingbox")
                ->whereIN("idDynamicObject", $idDynamicObjectList)
                ->all();
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox->idDynamicObject][] = $bbox;
            }
        }
        $order = 0;
        foreach ($objects as $object) {
            $object->order = ++$order;
            $object->bgColorGL = '#' . $object->bgColorGL;
            $object->fgColorGL = '#' . $object->fgColorGL;
            $object->startTime = (int)($object->startTime * 1000);
            $object->endTime = (int)($object->endTime * 1000);
            $object->bboxes = $bboxes[$object->idDynamicObject] ?? [];
        }
        $objectsRows = [];
        $objectsRowsEnd = [];
        $idLayerTypeCurrent = 0;
        foreach ($objects as $i => $object) {
            if ($object->idLayerType != $idLayerTypeCurrent) {
                $idLayerTypeCurrent = $object->idLayerType;
                $objectsRows[$object->idLayerType][0][] = $object;
                $objectsRowsEnd[$object->idLayerType][0] = $object->endFrame;
            } else {
                $allocated = false;
                foreach($objectsRows[$object->idLayerType] as $idLayer => $objectRow) {
                    if ($object->startFrame > $objectsRowsEnd[$object->idLayerType][$idLayer]) {
                        $objectsRows[$object->idLayerType][$idLayer][] = $object;
                        $objectsRowsEnd[$object->idLayerType][$idLayer] = $object->endFrame;
                        $allocated = true;
                        break;
                    }
                }
                if (!$allocated) {
                    $idLayer = count($objectsRows[$object->idLayerType]);
                    $objectsRows[$object->idLayerType][$idLayer][] = $object;
                    $objectsRowsEnd[$object->idLayerType][$idLayer] = $object->endFrame;
                }

            }
        }

        $result = [];
        foreach($objectsRows as $idLayerType => $layers) {
            foreach($layers as $idLayer => $objects) {
               $result[] = [
                  'layer' => $objects[0]->nameLayerType,
                  'objects' => $objects
               ];
            }
        }
        return $result;
    }

    public static function updateObjectAnnotation(ObjectAnnotationData $data): object
    {
        $usertask = self::getCurrentUserTask($data->idDocument);
        $do = Criteria::byId("dynamicobject", "idDynamicObject", $data->idDynamicObject);
        Criteria::deleteById("annotation", "idAnnotationObject", $do->idAnnotationObject);
        if ($data->idFrameElement) {
            $fe = Criteria::byId("frameelement", "idFrameElement", $data->idFrameElement);
            $json = json_encode([
                'idEntity' => $fe->idEntity,
                'idAnnotationObject' => $do->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        if ($data->idLU) {
            $lu = Criteria::byId("lu", "idLU", $data->idLU);
            $json = json_encode([
                'idEntity' => $lu->idEntity,
                'idAnnotationObject' => $do->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        if ($data->idGenericLabel) {
            $gl = Criteria::byId("genericlabel", "idGenericLabel", $data->idGenericLabel);
            $json = json_encode([
                'idEntity' => $gl->idEntity,
                'idAnnotationObject' => $do->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        return $do;
    }

    public static function updateObject(ObjectData $data): int
    {
        $idUser = AppService::getCurrentIdUser();
        // if idDynamicObject = null : object create
        if (is_null($data->idDynamicObject)) {
            $do = json_encode([
                'name' => $data->name,
                'startFrame' => (int)$data->startFrame,
                'endFrame' => (int)$data->endFrame,
                'startTime' => (float)$data->startTime,
                'endTime' => (float)$data->endTime,
                'status' => (int)$data->status,
                'origin' => (int)$data->origin,
                'idUser' => $idUser
            ]);
            $idDynamicObject = Criteria::function("dynamicobject_create(?)", [$do]);
            $dynamicObject = Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
            $documentVideo = Criteria::table("view_document_video")
                ->where("idDocument", $data->idDocument)
                ->first();
            $video = Video::byId($documentVideo->idVideo);
            Criteria::create("video_dynamicobject",[
                "idVideo" => $video->idVideo,
                "idDynamicObject" => $dynamicObject->idDynamicObject,
            ]);
            if (count($data->frames)) {
                foreach ($data->frames as $frame) {
                    $json = json_encode([
                        'frameNumber' => (int)$frame['frameNumber'],
                        'frameTime' => (float)$frame['frameTime'],
                        'x' => (int)$frame['x'],
                        'y' => (int)$frame['y'],
                        'width' => (int)$frame['width'],
                        'height' => (int)$frame['height'],
                        'blocked' => (int)$frame['blocked'],
                        'idDynamicObject' => (int)$idDynamicObject
                    ]);
                    $idBoundingBox = Criteria::function("boundingbox_dynamic_create(?)", [$json]);
                }
            }
        } else {
            // if idDynamicObject != null : update object and  boundingboxes
            $idDynamicObject = $data->idDynamicObject;
            Criteria::table("dynamicobject")
                ->where("idDynamicObject", $idDynamicObject)
                ->update([
                    'startFrame' => $data->startFrame,
                    'endFrame' => $data->endFrame,
                    'startTime' => $data->startTime,
                    'endTime' => $data->endTime,
                ]);
        }
        return $idDynamicObject;
    }

    public static function cloneObject(CloneData $data): int
    {
        $idUser = AppService::getCurrentIdUser();
        $idDynamicObject = $data->idDynamicObject;
        $do = self::getObject($idDynamicObject);
        $clone = json_encode([
            'name' => $do->name,
            'startFrame' => (int)$do->startFrame,
            'endFrame' => (int)$do->endFrame,
            'startTime' => (float)$do->startTime,
            'endTime' => (float)$do->endTime,
            'status' => (int)$do->status,
            'origin' => (int)$do->origin,
            'idUser' => $idUser
        ]);
        $idDynamicObjectClone = Criteria::function("dynamicobject_create(?)", [$clone]);
        $dynamicObjectClone = Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObjectClone);
        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $data->idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        Criteria::create("video_dynamicobject",[
            "idVideo" => $video->idVideo,
            "idDynamicObject" => $dynamicObjectClone->idDynamicObject,
        ]);
        // cloning bboxes
        $bboxes = Criteria::table("view_dynamicobject_boundingbox")
            ->where("idDynamicObject", $idDynamicObject)
            ->all();
        foreach ($bboxes as $bbox) {
            $json = json_encode([
                'frameNumber' => (int)$bbox->frameNumber,
                'frameTime' => (float)$bbox->frameTime,
                'x' => (int)$bbox->x,
                'y' => (int)$bbox->y,
                'width' => (int)$bbox->width,
                'height' => (int)$bbox->height,
                'blocked' => (int)$bbox->blocked,
                'idDynamicObject' => (int)$idDynamicObjectClone
            ]);
            $idBoundingBox = Criteria::function("boundingbox_dynamic_create(?)", [$json]);
        }
        return $idDynamicObjectClone;
    }

    public static function deleteObject(int $idDynamicObject): void
    {
        // se pode remover o objeto se for Manager ou se for o criador do objeto
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        if (!User::isManager($user)) {
            $tl = Criteria::table("timeline")
                ->where("tablename", "dynamicobject")
                ->where("id", $idDynamicObject)
                ->select("idUser")
                ->first();
            if ($tl->idUser != $idUser) {
                throw new \Exception("Object can not be removed.");
            }
        }
        DB::transaction(function () use ($idDynamicObject) {
            // remove boundingbox
            self::deleteBBoxesByDynamicObject($idDynamicObject);
            // remove dynamicobject
            $idUser = AppService::getCurrentIdUser();
            Criteria::function("dynamicobject_delete(?,?)", [$idDynamicObject, $idUser]);
        });
    }

    public static function updateObjectFrame(ObjectFrameData $data): int
    {
        Criteria::table("dynamicobject")
            ->where("idDynamicObject", $data->idDynamicObject)
            ->update($data->toArray());
        return $data->idDynamicObject;
    }

    public static function updateBBox(UpdateBBoxData $data): int
    {
        Criteria::table("boundingbox")
            ->where("idBoundingBox", $data->idBoundingBox)
            ->update($data->bbox);
        return $data->idBoundingBox;
    }

    public static function createBBox(CreateBBoxData $data): int
    {
        $dynamicObject = Criteria::byId("dynamicobject", "idDynamicObject", $data->idDynamicObject);
        if ($dynamicObject->endFrame < $data->frameNumber) {
            Criteria::table("dynamicobject")
                ->where("idDynamicObject", $data->idDynamicObject)
                ->update(['endFrame' => $data->frameNumber]);
        }
        $json = json_encode([
            'frameNumber' => (int)$data->frameNumber,
            'frameTime' => $data->frameNumber * 0.04,
            'x' => (int)$data->bbox['x'],
            'y' => (int)$data->bbox['y'],
            'width' => (int)$data->bbox['width'],
            'height' => (int)$data->bbox['height'],
            'blocked' => (int)$data->bbox['blocked'],
            'idDynamicObject' => (int)$data->idDynamicObject
        ]);
        $idBoundingBox = Criteria::function("boundingbox_dynamic_create(?)", [$json]);
        return $idBoundingBox;
    }

    public static function deleteBBoxesFromObject(DeleteBBoxData $data): int
    {
        $idUser = AppService::getCurrentIdUser();
        $bboxes = Criteria::table("view_dynamicobject_boundingbox")
            ->where("idDynamicObject", $data->idDynamicObject)
            ->chunkResult("idBoundingBox","idBoundingBox");
        debug($bboxes);
        foreach ($bboxes as $idBoundingBox) {
            Criteria::function("boundingbox_dynamic_delete(?,?)", [$idBoundingBox, $idUser]);
        }
        return $data->idDynamicObject;
    }

}
