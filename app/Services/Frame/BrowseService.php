<?php

namespace App\Services\Frame;

use App\Database\Criteria;
use App\Repositories\Frame;
use App\Services\AppService;
use Illuminate\Database\Query\JoinClause;

class BrowseService
{
    public static function browseFrameBySearch(object $search): array
    {
        $result = [];
        $subQuery = Criteria::table('view_frame_classification')
            ->selectRaw('idFrame, group_concat(name) as domain')
            ->where('relationType', 'rel_framal_domain')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->groupBy('idFrame');
        $frames = Criteria::table('view_frame as f')
            ->where('f.name', 'startswith', $search->frame)
            ->where('f.idLanguage', AppService::getCurrentIdLanguage())
            ->joinSub($subQuery, 'domains', function (JoinClause $join) {
                $join->on('f.idFrame', '=', 'domains.idFrame');
            })
            ->orderBy('name')->all();
        foreach ($frames as $frame) {
            $result[$frame->idFrame] = [
                'id' => $frame->idFrame,
                'type' => 'frame',
                'text' => view('Frame.partials.frame_ns', ['frame' => $frame])->render(),
                'leaf' => true,
            ];
        }

        return $result;
    }

    public static function browseNamespaceFrameBySearch(object $search): array
    {
        // Fetch all namespaces
        $namespaces = Criteria::table('view_namespace')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->orderBy('name')
            ->all();

        // Build subquery for namespace classification
        $subQuery = Criteria::table('view_frame_classification as c')
            ->join('view_namespace as ns', 'c.idSemanticType', '=', 'ns.idSemanticType')
            ->selectRaw("c.idFrame, ns.name as namespace, ns.idSemanticType, concat('color_',lower(substr(ns.name,2))) as color")
            ->where('c.relationType', 'rel_namespace')
            ->where('c.idLanguage', AppService::getCurrentIdLanguage())
            ->where('ns.idLanguage', AppService::getCurrentIdLanguage());

        $subQueryDomains = Criteria::table('view_frame_classification')
            ->selectRaw('idFrame, group_concat(name) as domain')
            ->where('relationType', 'rel_framal_domain')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->groupBy('idFrame');

        // Query frames with namespace info
        $framesQuery = Criteria::table('view_frame as f')
            ->where('f.idLanguage', AppService::getCurrentIdLanguage())
            ->joinSub($subQuery, 'namespace', function (JoinClause $join) {
                $join->on('f.idFrame', '=', 'namespace.idFrame');
            })
            ->joinSub($subQueryDomains, 'domains', function (JoinClause $join) {
                $join->on('f.idFrame', '=', 'domains.idFrame');
            })
            ->orderBy('f.name');

        // Apply search filter if provided
        if (! empty($search->frame)) {
            $framesQuery->where('f.name', 'startswith', $search->frame);
        }

        $frames = $framesQuery->all();

        // Group frames by namespace
        $result = ['namespaces' => []];

        foreach ($namespaces as $namespace) {
            $namespaceData = [
                'idNamespace' => $namespace->idSemanticType,
                'name' => $namespace->name,
                'description' => $namespace->description,
                'color' => 'color_'.strtolower(substr($namespace->name, 1)),
                'frames' => [],
            ];

            // Filter frames for this namespace
            $namespaceFrames = array_filter($frames, function ($frame) use ($namespace) {
                return $frame->idSemanticType == $namespace->idSemanticType;
            });

            foreach ($namespaceFrames as $frame) {
                $frame->namespace = (object) [
                    'idNamespace' => $frame->idSemanticType,
                    'color' => $frame->color,
                ];
                $namespaceData['frames'][] = [
                    'id' => $frame->idFrame,
                    'name' => $frame->name,
                    'description' => $frame->description,
                    'domain' => $frame->domain,
                    'color' => $frame->color,
                    'frame' => $frame,
                ];
            }

            $namespaceData['count'] = count($namespaceData['frames']);

            // Only include namespaces with frames (when searching)
            if (empty($search->frame) || $namespaceData['count'] > 0) {
                $result['namespaces'][] = $namespaceData;
            }
        }

        return $result;
    }

    public static function browseLUBySearch(object $search, bool $leaf = true, bool $contains = false): array
    {
        $result = [];
        $op = $contains ? 'contains' : 'startswith';
        debug('op', $op);
        $lus = Criteria::byFilterLanguage('view_lu', ['name', $op, $search->lu], 'idLanguage')
            ->limit(300)
            ->orderBy('name')
            ->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => $lu->idLU,
                'type' => 'lu',
                'text' => view('Frame.partials.lu', (array) $lu)->render(),
                'leaf' => $leaf,
            ];
        }

        return $result;
    }

    public static function browseLUForReframingBySearch(object $search, bool $leaf = true): array
    {
        $result = [];
        if ($search->lu != '') {
            $lus = Criteria::byFilterLanguage('view_lu', ['name', 'startswith', $search->lu], 'idLanguage')
                ->limit(300)
                ->orderBy('name')
                ->all();
            foreach ($lus as $lu) {
                $result[$lu->idLU] = [
                    'id' => $lu->idLU,
                    'type' => 'lu',
                    'text' => view('LU.Reframing.partials.lu', (array) $lu)->render(),
                    'leaf' => $leaf,
                ];
            }
        }

        return $result;
    }
}
