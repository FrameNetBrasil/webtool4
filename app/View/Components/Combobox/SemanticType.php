<?php

namespace App\View\Components\Combobox;

use App\Database\Criteria;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use App\Repositories\SemanticType as SemanticTypeRepository;

class SemanticType extends Component
{
    public $list;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public string $placeholder = '',
        public string $root = ''
    )
    {
        $result = [];
        $list = $this->buildTree($root);
        foreach ($list as $i => $row) {
            $node = (array)$row;
//            $node['name'] = view('components.element.semantictype',['name' => $row->name])->render();
//            $node['state'] = 'closed';
//            $node['iconCls'] = '';
            $children = $this->buildTree($row['name']);
            $node['children'] = !empty($children) ? $children : null;
            $result[] = $node;
        }
        debug($result);
        $this->list = $result;
    }

    public function buildTree(string $root): array
    {
        //$list = SemanticTypeRepository::listForComboGrid($root);
        $st = Criteria::byFilterLanguage("view_semantictype",["name","=",$root])->first();
        $list = SemanticTypeRepository::listChildren($st->idEntity);
        $result = [];
        foreach ($list as $row) {
            debug($row);
            $result[] = [
                'idSemanticType' => $row->idSemanticType,
                'name' => $row->name,
                'html' => view('components.element.semantictype', ['name' => $row->name])->render(),
                'state' => 'open',
                'iconCls' => ''
            ];
        }
        return $result;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.semantic-type');
    }
}
