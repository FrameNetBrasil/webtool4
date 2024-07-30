<?php

namespace App\View\Components\Combobox;

use App\Database\Criteria;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Services\FrameService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class FeFrame extends Component
{
    public array $options;
    public string $default = '';

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string  $id,
        public string  $label,
        public int     $idFrame,
        public ?string $value = null,
        public ?string $name = null,
        public ?string $nullName = null,
        public bool    $hasNull = false
    )
    {
        if (is_null($this->name)) {
            $this->name = $this->id;
        }
        $this->value = $this->value ?? $this->nullName ?? '';
        $this->options = [];
        if ($idFrame > 0) {
            $fes = Criteria::byFilterLanguage("view_frameelement", ["idFrame", "=", $idFrame])->all();
            if ($this->hasNull) {
                $this->options[] = [
                    'idFrameElement' => '-1',
                    'name' => $this->nullName ?? "NULL",
                    'coreType' => '',
                    'idColor' => "color_1"
                ];
            }
            foreach ($fes as $fe) {
                if ($this->value == $fe->idFrameElement) {
                    $this->default = $fe->name;
                }
                $this->options[] = [
                    'idFrameElement' => $fe->idFrameElement,
                    'name' => $fe->name,
                    'coreType' => $fe->coreType,
                    'idColor' => $fe->idColor
                ];
            }

        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.fe-frame');
    }
}