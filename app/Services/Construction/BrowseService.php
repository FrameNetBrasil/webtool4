<?php

namespace App\Services\Construction;

use App\Database\Criteria;
use App\Services\AppService;

class BrowseService
{
    public static function browseCxnBySearch(object $search, bool $leaf = true): array
    {
        $result = [];
        $cxns = Criteria::table("view_construction as c")
            ->where('c.name', "startswith", $search->cxn)
            ->where('c.cxIdLanguage', "=", $search->cxIdLanguage)
            ->where("c.idLanguage", AppService::getCurrentIdLanguage())
            ->orderBy('name')->all();
        foreach ($cxns as $cxn) {
            $result[$cxn->idConstruction] = [
                'id' => $cxn->idConstruction,
                'type' => 'cxn',
                'text' => view('Construction.partials.cxn',(array)$cxn)->render(),
                'leaf' => $leaf,
            ];
        }
        return $result;
    }

}
