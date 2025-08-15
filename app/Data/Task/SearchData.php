<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $task = '',
        public ?string $user = '',
        public ?string $type = '',
        public ?int $id = 0,
        public ?int $idTask = 0,
        public ?int $idUser = 0,
        public string $_token = '',
    )
    {
        if ($type == 'task') {
            $this->idTask = $this->id;
        } elseif ($type == 'user') {
            $this->idUser = $this->id;
        }
        $this->_token = csrf_token();
    }

}
