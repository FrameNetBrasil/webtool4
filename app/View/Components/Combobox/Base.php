<?php

namespace App\View\Components\Combobox;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Base extends Component
{
    public array $options;
    public function __construct(
        public string $id,
        public ?string $label = '',
        public string $value = '',
        public string $defaultText = '',
    )
    {
        $this->options = [];
    }

    public function render(): View|Closure|string
    {
        return view('components.combobox.base');
    }
}
