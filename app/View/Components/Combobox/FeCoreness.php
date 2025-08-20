<?php

namespace App\View\Components\Combobox;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeCoreness extends Base
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
            $this->defaultText = "Select coreness";
        }
        if ($this->label === '') {
            $this->label = "Coreness";
        }
        $coreness = config('webtool.fe.coreness');
        $this->options = [];
        foreach ($coreness as $entry => $coreType) {
            $this->options[$entry] = $coreType;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.combobox.fe-coreness');
    }
}
