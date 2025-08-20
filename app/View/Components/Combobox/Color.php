<?php

namespace App\View\Components\Combobox;

use App\Database\Criteria;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Color extends Base
{
    public function __construct(
        public string $id,
        public ?string $label = '',
        public string $value = '',
        public string $defaultText = '',
    )
    {
        parent::__construct($id, $label, $value, $defaultText);
        if ($this->defaultText == '') {
            $this->defaultText = "Select color";
        }
        if ($this->label === '') {
            $this->label = "Color";
        }
        $list = Criteria::table("color")->orderBy("rgbBg")->all();
        $this->options = [];
        foreach($list as $c) {
            if ($this->value == $c->idColor) {
                $this->defaultText = $c->name;
            }
            $this->options[] = [
                'id' => $c->idColor,
                'text' => $c->name,
                'color' => "color_{$c->idColor}"
            ];
        }
    }
    public function render(): View|Closure|string
    {
        return view('components.combobox.color');
    }
}
