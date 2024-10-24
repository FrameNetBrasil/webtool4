<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\Domain\SearchData as DomainSearchData;
use App\Data\SemanticType\CreateData;
use App\Data\SemanticType\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\SemanticType;
use App\Services\AppService;
use App\Services\RelationService;
use App\View\Components\Combobox\Domain;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class ResourceController extends Controller
{

    #[Get(path: '/semanticType')]
    public function resource()
    {
        return view("SemanticType.resource");
    }

    #[Post(path: '/semanticType/grid')]
    #[Get(path: '/semanticType/grid')]
    public function grid(SearchData $search)
    {
        debug($search);
        return view("SemanticType.grid", [
            'search' => $search
        ]);
    }

    #[Post(path: '/semanticType/listForTree')]
    public function listForTree(SearchData $search)
    {
        debug($search);
        $domainIcon = view('components.icon.corpus')->render();
        $stIcon = view('components.icon.semantictype')->render();
        $result = [];
        if ($search->semanticType != '') {
            $st = Criteria::table("view_semanticType")
                ->select("idSemanticType", "idEntity", "name")
                ->where("name","startswith",'@'.$search->semanticType)
                ->where('idLanguage', '=', AppService::getCurrentIdLanguage())
                ->orderBy("name")->all();
            foreach ($st as $row) {
                $node = [];
                $node['id'] = 't' . $row->idEntity;
                $node['idSemanticType'] = $row->idSemanticType;
                $node['type'] = 'semanticType';
                $node['text'] = $stIcon . $row->name;
                $node['state'] = 'closed';
                $node['children'] = [];
                $result[] = $node;
            }
        } else {

            if (($search->idDomain == 0) && ($search->idSemanticType == 0)) {
                $domains = SemanticType::listDomains();
                foreach ($domains as $row) {
                    $node = [];
                    $node['id'] = 'd' . $row->idDomain;
                    $node['idDomain'] = $row->idDomain;
                    $node['type'] = 'domain';
                    $node['text'] = $domainIcon . $row->name;
                    $node['state'] = 'closed';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                if ($search->idDomain != 0) {
                    $st = SemanticType::listRootByDomain($search->idDomain);
                    foreach ($st as $row) {
                        $node = [];
                        $node['id'] = 't' . $row->idEntity;
                        $node['idSemanticType'] = $row->idSemanticType;
                        $node['type'] = 'semanticType';
                        $node['text'] = $stIcon . $row->name;
                        $node['state'] = 'closed';
                        $node['children'] = [];
                        $result[] = $node;
                    }
                } else {
                    $st = SemanticType::listChildren($search->idSemanticType);
                    foreach ($st as $row) {
                        $node = [];
                        $node['id'] = 't' . $row->idEntity;
                        $node['idSemanticType'] = $row->idSemanticType;
                        $node['type'] = 'semanticType';
                        $node['text'] = $stIcon . $row->name;
                        $node['state'] = 'closed';
                        $node['children'] = [];
                        $result[] = $node;
                    }
                }
            }
        }
        return $result;
    }



    #[Get(path: '/semanticType/{id}/semanticTypes')]
    public function semanticTypes(string $id)
    {
        $semanticType = SemanticType::getById($id);
        return view("SemanticType.childSubType",[
            'idEntity' => $semanticType->idEntity,
            'root' => $semanticType->name
        ]);
    }


    /***
     * Master
     */

    #[Get(path: '/semanticType/{id}')]
    public function get(string $id)
    {
        return view("SemanticType.edit", [
            'semanticType' => SemanticType::byId($id),
        ]);
    }


    /***
     * Child
     */
    #[Get(path: '/semanticType/{idEntity}/childAdd/{root}')]
    public function childFormAdd(string $idEntity, string $root)
    {
        return view("SemanticType.childAdd", [
            'idEntity' => $idEntity,
            'root' => $root
        ]);
    }

    #[Get(path: '/semanticType/{idEntity}/childGrid')]
    public function childGrid(string $idEntity)
    {
        $relations = SemanticType::listRelations($idEntity);
        return view("SemanticType.childGrid", [
            'idEntity' => $idEntity,
            'relations' => $relations
        ]);
    }

    #[Post(path: '/semanticType/{idEntity}/add')]
    public function childAdd(CreateData $data)
    {
        try {
            $st = SemanticType::byId($data->idSemanticType);
            RelationService::create(
                'rel_hassemtype',
                $data->idEntity,
                $st->idEntity,
                null,
                null,
                $data->idUser
            );
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semanticType/{idEntityRelation}')]
    public function childDelete(int $idEntityRelation)
    {
        try {
            Criteria::table("entityrelation")->where("idEntityRelation", $idEntityRelation)->delete();
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/semanticType/{idEntity}/childAddSubType/{root}')]
    public function childFormAddSubType(string $idEntity, string $root)
    {
        return view("SemanticType.childAddSubType", [
            'idEntity' => $idEntity,
            'root' => $root
        ]);
    }

    #[Get(path: '/semanticType/{idEntity}/childGridSubType')]
    public function childGridSubType(string $idEntity)
    {
        return view("SemanticType.childGridSubType", [
            'idEntity' => $idEntity,
            'relations' => SemanticType::listRelations($idEntity)->all()
        ]);
    }


}
