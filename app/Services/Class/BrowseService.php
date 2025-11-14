<?php

namespace App\Services\Class;

use App\Database\Criteria;
use App\Services\AppService;

class BrowseService
{
    public static function browseClassBySearch(object $search): array
    {
        debug($search);
        $result = [];
        $classes = Criteria::table('view_class as c')
            ->where('c.name', 'startswith', $search->class)
            ->where('c.idLanguage', AppService::getCurrentIdLanguage())
            ->orderBy('name')->all();
        foreach ($classes as $class) {
            if (strlen($class->description) > 300) {
                $class->description = substr($class->description, 0, 300).' ...';
            }
            $result[$class->idClass] = [
                'id' => $class->idClass,
                'type' => 'class',
                'text' => view('Class.partials.class', ['class' => $class])->render(),
                'leaf' => true,
            ];
        }

        return $result;
    }
}
