<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\SemanticType\SearchData;
use App\Data\Domain\SearchData as DomainSearchData;
use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\SemanticType;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/semanticType')]
    public function browse()
    {
        $search = session('searchSemanticType') ?? SearchData::from();
        return view("SemanticType.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/semanticType/grid')]
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
        $result = [];
        if (($search->idDomain == 0) && ($search->idSemanticType == 0)) {
            $search = DomainSearchData::from($search);
            $domains = Domain::listToGrid($search);
            foreach ($domains as $row) {
                $node = [];
                $node['id'] = 'd' . $row->idDomain;
                $node['idDomain'] = $row->idDomain;
                $node['type'] = 'domain';
                $node['name'] = $row->name;
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                $node['children'] = [];
                $result[] = $node;
            }
        } else {
            $st = SemanticType::listForTree($search);
            foreach ($st as $row) {
                $node = [];
                $node['id'] = 't' . $row->idSemanticType;
                $node['idSemanticType'] = $row->idSemanticType;
                $node['type'] = 'semanticType';
                $node['name'] = [$row->name, $row->description ?? '', '', '', ''];
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-semantictype';
                $node['children'] = [];
                $result[] = $node;
            }

        }
        return $result;
    }

}
