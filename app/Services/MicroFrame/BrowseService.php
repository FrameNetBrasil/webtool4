<?php

namespace App\Services\MicroFrame;

use App\Database\Criteria;
use App\Services\AppService;

class BrowseService
{
    public static function browseMicroFrameBySearch(object $search): array
    {
        debug($search);
        $result = [];
        $microframes = Criteria::table('view_microframe as m')
            ->where('m.name', 'startswith', $search->microframe)
            ->where('.idLanguage', AppService::getCurrentIdLanguage())
            ->orderBy('name')->all();
        foreach ($microframes as $microframe) {
            if (strlen($microframe->description) > 300) {
                $microframe->description = substr($microframe->description, 0, 300).' ...';
            }
            $result[$microframe->idMicroFrame] = [
                'id' => $microframe->idMicroFrame,
                'type' => 'class',
                'text' => view('MicroFrame.partials.microframe', ['microframe' => $microframe])->render(),
                'leaf' => true,
            ];
        }

        return $result;
    }
}
