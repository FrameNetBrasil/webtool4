<?php

namespace App\View\Components\Checkbox;

use App\Database\Criteria;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use App\Services\AppService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FramalNamespace extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public int    $idFrame,
        public array  $options = []
    )
    {
        $frame = Frame::byId($this->idFrame);
        $namespaces = Criteria::table("view_namespace")
            ->where("idLanguage",AppService::getCurrentIdLanguage())
            ->orderBy("name")
            ->all();
        $this->options = [];
        foreach ($namespaces as $namespace) {
            $this->options[] = [
                'value' => $namespace->idNamespace,
                'name' => $namespace->name,
                'checked' => ($frame->idNamespace == $namespace->idNamespace) ? 'checked' : '',
                'disable' => false,
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.checkbox.framal-namespace');
    }
}
