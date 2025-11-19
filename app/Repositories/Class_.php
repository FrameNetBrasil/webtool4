<?php

namespace App\Repositories;

use App\Database\Criteria;

class Class_
{
    public static function byId(int $id): object
    {
        $frame = Criteria::byFilterLanguage("view_class", ['idFrame', '=', $id])->first();
        return $frame;
    }

    public static function byIdEntity(int $idEntity): object
    {
        return Criteria::byFilterLanguage("view_class", ['idEntity', '=', $idEntity])->first();
    }

}
