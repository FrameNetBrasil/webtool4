<?php

namespace App\View\Components\Combobox;

use App\Database\Criteria;
use App\Repositories\RelationType;
use App\Services\AppService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Relation extends Component
{
    public array $options;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $group,
        public ?string $value = ''
    )
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $groupEntries = [
            'frame' => 'rgp_frame_relations',
            'fe' => 'rgp_fe_relations',
            'cxn' => 'rgp_cxn_relations',
            'constraints' => 'rgp_constraints',
            'qualia' => 'rgp_qualia'
        ];
        $relations = Criteria::table("view_relationtype")
            ->where('rgEntry', $groupEntries[$this->group])
            ->where('idLanguage', $idLanguage)
            ->all();
        $this->options = [];
        $config = config('webtool.relations');
        if ($group == 'frame') {
            $this->options[] = [
                'value' => 'header',
                'entry' => '0',
                'name' => 'Direct relation',
            ];
        }
        foreach($relations as $relation) {
            $this->options[] = [
                'value' => 'd' . $relation->idRelationType,
                'entry' => $relation->entry,
                'name' => $config[$relation->entry]['direct'],
            ];
        }
        if ($group == 'frame') {
            $this->options[] = [
                'value' => 'header',
                'entry' => '0',
                'name' => 'Inverse relation',
            ];
            foreach ($relations as $relation) {
                $this->options[] = [
                    'value' => 'i' . $relation->idRelationType,
                    'entry' => $relation->entry,
                    'name' => $config[$relation->entry]['inverse'],
                ];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.relation');
    }
}
