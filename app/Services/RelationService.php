<?php

namespace App\Services;

use App\Data\CreateRelationFEInternalData;
use App\Data\Frame\UpdateClassificationData;
use App\Data\Relation\CreateData;
use App\Data\UpdateFrameClassificationData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\Relation;
use App\Repositories\RelationType;
use App\Repositories\SemanticType;
use App\Repositories\ViewRelation;

class RelationService extends Controller
{
    public static function delete(int $id)
    {
        Relation::delete($id);
    }

    public static function newRelation(CreateData $data): ?int
    {
        return Relation::save($data);
    }

    static public function create(string $relationTypeEntry, int $idEntity1, int $idEntity2, ?int $idEntity3 = null, ?int $idRelation = null): ?int
    {
        $user = AppService::getCurrentUser();
        $data = json_encode([
            'relationType' => $relationTypeEntry,
            'idEntity1' => $idEntity1,
            'idEntity2' => $idEntity2,
            'idEntity3' => $idEntity3 ?? null,
            'idRelation' => $idRelation ?? null,
            'idUser' => $user ? $user->idUser : 0
        ]);
        return Criteria::function('relation_create(?)', [$data]);
    }

    public static function listRelationsFrame(int $idFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $config = config('webtool.relations');
        $result = [];
        $relations = Criteria::table("view_frame_relation")
            ->where("f1IdFrame", $idFrame)
            ->where("idLanguage", $idLanguage)
            ->orderBy("relationType")
            ->orderBy("f2Name")
            ->all();
        foreach ($relations as $relation) {
            $result[] = (object)[
                'idEntityRelation' => $relation->idEntityRelation,
                'relationType' => $relation->relationType,
                'name' => $config[$relation->relationType]['direct'],
                'color' => $config[$relation->relationType]['color'],
                'idFrameRelated' => $relation->f2IdFrame,
                'related' => $relation->f2Name,
                'direction' => 'direct'
            ];
        }
        $inverse = Criteria::table("view_frame_relation")
            ->where("f2IdFrame", $idFrame)
            ->where("idLanguage", $idLanguage)
            ->all();
        foreach ($inverse as $relation) {
            $result[] = (object)[
                'idEntityRelation' => $relation->idEntityRelation,
                'relationType' => $relation->relationType,
                'name' => $config[$relation->relationType]['inverse'],
                'color' => $config[$relation->relationType]['color'],
                'idFrameRelated' => $relation->f1IdFrame,
                'related' => $relation->f1Name,
                'direction' => 'inverse'
            ];
        }
        return $result;
    }

    public static function listRelationsFEInternal(int $idFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $config = config('webtool.relations');
        $relations = Criteria::table("view_fe_internal_relation")
            ->where("fe1IdFrame", $idFrame)
            ->where("idLanguage", $idLanguage)
            ->all();
        $result = [];
        foreach ($relations as $relation) {
            $result[] = (object)[
                'idEntityRelation' => $relation->idEntityRelation,
                'relationType' => $relation->relationType,
                'feIdFrameElement' => $relation->fe1IdFrameElement,
                'feName' => $relation->fe1Name,
                'feIdColor' => $relation->fe1IdColor,
                'feCoreType' => $relation->fe1CoreType,
                'relatedFEIdFrameElement' => $relation->fe2IdFrameElement,
                'relatedFEName' => $relation->fe2Name,
                'relatedFEIdColor' => $relation->fe2IdColor,
                'relatedFECoreType' => $relation->fe2CoreType,
                'name' => $config[$relation->relationType]['direct'],
                'color' => $config[$relation->relationType]['color'],
            ];
        }
        return $result;
    }

    public static function listRelationsFE(int $idEntityRelationBase)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $config = config('webtool.relations');
        $relations = Criteria::table("view_fe_relation")
            ->where("idRelation", $idEntityRelationBase)
            ->where("idLanguage", $idLanguage)
            ->all();
        $result = [];
        foreach ($relations as $relation) {
            $result[] = (object)[
                'idEntityRelation' => $relation->idEntityRelation,
                'entry' => $relation->relationType,
                'relationName' => $config[$relation->relationType]['direct'],
                'color' => $config[$relation->relationType]['color'],
                'feName' => $relation->fe1Name,
                'feCoreType' => $relation->fe1CoreType,
                'feIdColor' => $relation->fe1IdColor,
                'relatedFEName' => $relation->fe2Name,
                'relatedFECoreType' => $relation->fe2CoreType,
                'relatedFEIdColor' => $relation->fe2IdColor,
            ];
        }
        return $result;
    }


//    static public function createForIdRelationType(int $idRelationType, int $idEntity1, int $idEntity2, ?int $idEntity3 = null, ?int $idRelation = null): ?int
//    {
//        $relationData = CreateData::from([
//            'idRelationType' => $idRelationType,
//            'idEntity1' => $idEntity1,
//            'idEntity2' => $idEntity2,
//            'idEntity3' => $idEntity3,
//            'idRelation' => $idRelation
//        ]);
//        return self::newRelation($relationData);
//    }
//
//    static public function deleteAll(int $idEntity)
//    {
//        $subCriteria = Relation::getCriteria()
//            ->select('idEntityRelation')
//            ->orWhere('idEntity1',$idEntity)
//            ->orWhere('idEntity2',$idEntity)
//            ->orWhere('idEntity3',$idEntity);
//        Relation::getCriteria()
//            ->where('idRelation', 'IN', $subCriteria)
//            ->delete();
//        Relation::getCriteria()
//            ->where('idEntity3', '=', $idEntity)
//            ->delete();
//        Relation::getCriteria()
//            ->where('idEntity2', '=', $idEntity)
//            ->delete();
//        Relation::getCriteria()
//            ->where('idEntity1', '=', $idEntity)
//            ->delete();
//    }

//

//


    public static function updateFramalDomain(UpdateClassificationData $data)
    {
        $frame = Frame::byId($data->idFrame);
        $relationType = Criteria::byId("relationtype", "entry", "rel_framal_domain");
        try {
            Criteria::table("entityrelation")
                ->where("idEntity1", $frame->idEntity)
                ->where("idRelationType", $relationType->idRelationType)
                ->delete();
            foreach ($data->framalDomain as $idSemanticType) {
                $st = SemanticType::byId($idSemanticType);
                self::create("rel_framal_domain", $frame->idEntity, $st->idEntity);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error updating relations. " . $e);
        }
    }

    public static function updateFramalType(UpdateClassificationData $data)
    {
        $frame = Frame::byId($data->idFrame);
        $relationType = Criteria::byId("relationtype", "entry", "rel_framal_type");
        try {
            Criteria::table("entityrelation")
                ->where("idEntity1", $frame->idEntity)
                ->where("idRelationType", $relationType->idRelationType)
                ->delete();
            foreach ($data->framalType as $idSemanticType) {
                $st = SemanticType::byId($idSemanticType);
                self::create("rel_framal_type", $frame->idEntity, $st->idEntity);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error updating relations. " . $e);
        }
    }

    /*
 * Graph
 */

    public static function listFrameRelationsForGraph(array $idArray, array $idRelationType)
    {
        $nodes = [];
        $links = [];
        debug($idRelationType);
        $idLanguage = AppService::getCurrentIdLanguage();
        foreach ($idArray as $idEntity) {
            $partial =  Criteria::table("view_relation as r")
                ->join("view_frame as f1","r.idEntity1","=","f1.idEntity")
                ->join("view_frame as f2","r.idEntity2","=","f2.idEntity")
                ->select('r.idEntityRelation','r.idRelationType', 'r.relationType', 'r.entity1Type', 'r.entity2Type', 'r.idEntity1', 'r.idEntity2',
                    'f1.name as frame1Name',
                    'f2.name as frame2Name',
                )->where('f1.idLanguage', '=', $idLanguage)
                ->where('f2.idLanguage', '=', $idLanguage)
                ->whereRaw("((r.idEntity1 = {$idEntity}) or (r.idEntity2 = {$idEntity}))")
                ->all();
            foreach ($partial as $r) {
                if (in_array($r->idRelationType, $idRelationType)) {
                    $nodes[$r->idEntity1] = [
                        'type' => 'frame',
                        'name' => $r->frame1Name
                    ];
                    $nodes[$r->idEntity2] = [
                        'type' => 'frame',
                        'name' => $r->frame2Name
                    ];
                    $links[$r->idEntity1][$r->idEntity2] = [
                        'type' => 'ff',
                        'idEntityRelation' => $r->idEntityRelation,
                        'relationEntry' => $r->relationType,
                    ];
                }
            }
        }
        return [
            'nodes' => $nodes,
            'links' => $links
        ];
    }

    public static function listFrameFERelationsForGraph(int $idEntityRelation)
    {
        $nodes = [];
        $links = [];
        $baseRelation = new ViewRelation($idEntityRelation);
        $icon = config('webtool.fe.icon.grapher');
        $frame = new Frame();
        $relations = $frame->listFEDirectRelations($idEntityRelation);
        foreach ($relations as $relation) {
            $nodes[$relation['feIdEntity']] = [
                'type' => 'fe',
                'name' => $relation['feName'],
                'icon' => $icon[$relation['feCoreType']],
                'idColor' => $relation['feIdColor']
            ];
            $nodes[$relation['relatedFEIdEntity']] = [
                'type' => 'fe',
                'name' => $relation['relatedFEName'],
                'icon' => $icon[$relation['relatedFECoreType']],
                'idColor' => $relation['relatedFEIdColor']
            ];
            $links[$baseRelation->idEntity1][$relation['feIdEntity']] = [
                'type' => 'ffe',
                'idEntityRelation' => $idEntityRelation,
                'relationEntry' => 'rel_has_element',
            ];
            $links[$relation['relatedFEIdEntity']][$baseRelation->idEntity2] = [
                'type' => 'ffe',
                'idEntityRelation' => $idEntityRelation,
                'relationEntry' => 'rel_has_element',
            ];
            $links[$relation['feIdEntity']][$relation['relatedFEIdEntity']] = [
                'type' => 'fefe',
                'idEntityRelation' => $relation['idEntityRelation'],
                'relationEntry' => $relation['entry'],
            ];
        }
        return [
            'nodes' => $nodes,
            'links' => $links
        ];
    }

    public static function listDomainForGraph(int $idSemanticType, array $frameRelation): array
    {
        $nodes = [];
        $links = [];
        $idLanguage = AppService::getCurrentIdLanguage();
        if ($idSemanticType > 0) {
            $semanticType = SemanticType::byId($idSemanticType);
            $frames = Criteria::table("view_relation as r")
                ->join("view_frame as f","r.idEntity1","=","f.idEntity")
                ->select("r.idEntity1 as idEntity", "f.name")
                ->where("r.relationType", "=", "rel_framal_domain")
                ->where("r.idEntity2", "=", $semanticType->idEntity)
                ->where("f.idLanguage", "=", $idLanguage)
                ->orderBy('f.name')
                ->all();
            $list = [];
            foreach ($frames as $frame) {
                $list[$frame->idEntity] = $frame->idEntity;
            }
            foreach ($frames as $frame) {
                $idEntity = $frame->idEntity;
                $partial = Criteria::table("view_relation as r")
                    ->join("view_frame as f1","r.idEntity1","=","f1.idEntity")
                    ->join("view_frame as f2","r.idEntity2","=","f2.idEntity")
                    ->select('r.idEntityRelation','r.idRelationType', 'r.relationType', 'r.entity1Type', 'r.entity2Type', 'r.idEntity1', 'r.idEntity2',
                        'f1.name as frame1Name',
                        'f2.name as frame2Name',
                    )->where('f1.idLanguage', '=', $idLanguage)
                    ->where('f2.idLanguage', '=', $idLanguage)
                    ->whereRaw("((r.idEntity1 = {$idEntity}) or (r.idEntity2 = {$idEntity}))")
                    ->all();
                foreach ($partial as $r) {
                    $ok = isset($list[$r->idEntity1]) && isset($list[$r->idEntity2]);
                    if ($ok) {
                        if (in_array($r->idRelationType, $frameRelation)) {
                            $nodes[$r->idEntity1] = [
                                'type' => 'frame',
                                'name' => $r->frame1Name
                            ];
                            $nodes[$r->idEntity2] = [
                                'type' => 'frame',
                                'name' => $r->frame2Name
                            ];
                            $links[$r->idEntity1][$r->idEntity2] = [
                                'type' => 'ff',
                                'idEntityRelation' => $r->idEntityRelation,
                                'relationEntry' => $r->relationType,
                            ];
                        }
                    }
                }
            }
        }
        return [
            'nodes' => $nodes,
            'links' => $links
        ];
    }

}
