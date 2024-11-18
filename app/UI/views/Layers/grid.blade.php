@php
    use App\Database\Criteria;
    $groupIcon = view('components.icon.layergroup')->render();
    $layerIcon = view('components.icon.layertype')->render();
    $labelIcon = view('components.icon.genericlabel')->render();
    $limit = 300;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
    $data = [];
    $layergroups = Criteria::table("layergroup")
        ->join("layertype","layergroup.idLayerGroup","=","layerType.idLayerGroup")
        ->select("layergroup.idLayerGroup","layergroup.name")
        ->selectRaw("count(layertype.idLayerType) as n")
        ->groupBy("layergroup.idLayerGroup","layergroup.name")
        ->having("n",">",0)
        ->orderBy("name")
        ->all();
    if ($search->genericlabel == '') {
        if ($search->layer == '') {
            $search->layer = '--none';
        }
        $data = [];
        foreach($layergroups as $layergroup) {
            $lt = [];
            $layers = Criteria::table("view_layertype")
                ->select("idLayerType","name")
                ->where("idLayerGroup",$layergroup->idLayerGroup)
                ->where("idLanguage",$idLanguage)
                ->all();
            foreach($layers as $layer) {
                $gl = [];
                $gls = Criteria::table("genericlabel")
                    ->select("idGenericLabel","name")
                    ->where("idLayerType",$layer->idLayerType)
                    ->where("idLanguage",$idLanguage)
                    ->all();
                foreach($gls as $g) {
                     $gl[] = [
                        'id' => 'g'. $g->idGenericLabel,
                        'text' => $labelIcon . $g->name,
                        'state' => 'open',
                        'type' => 'genericlabel',
                    ];
                }
                $lt[] = [
                    'id' => 'l' . $layer->idLayerType,
                    'text' => $layerIcon . $layer->name,
                    'state' => 'closed',
                    'type' => 'layer',
                    'children' => $gl
                ];
            }
            $data[] = [
                    'id' => 'lg' . $layergroup->idLayerGroup,
                    'text' => $groupIcon . $layergroup->name,
                    'state' => 'closed',
                    'type' => 'layergroup',
                    'children' => $lt
            ];
        }
        debug($data);
    } else {
        $genericlabels = Criteria::table("genericlabel as gl")
            ->join("view_layerType lt","lt.idLayterType","=","gl.idLayerType")
            ->select('idGenericLabel', 'name', "lt.name as layerName")
            ->where("gl.name", "startswith", $search->genericlabel)
            ->where('gl.idLanguage', "=", $idLanguage)
            ->where('lt.idLanguage', "=", $idLanguage)
            ->distinct()
            ->limit($limit)
            ->orderBy("name")->orderBy("lexeme")->all();
        foreach($genericlabels as $genericlabel) {
            $data[] = [
                'id' => $genericlabel->idGenericLabel,
                'text' => $genericlabel->name . " [{$genericlabel->layerName}]",
                'state' => 'closed',
                'type' => 'lexeme',
            ];
        }
    }
    if (empty($data)) {
         $data[] = [
            'id' => 0,
            'text' => "No results",
            'state' => 'closed',
            'type' => 'result',
        ];
    }
@endphp
<div
        class="h-full"
        hx-trigger="reload-gridLayers from:body"
        hx-target="this"
        hx-swap="innerHTML"
        hx-post="/layers/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="layersTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="layersTree">
                </ul>
                <script>
                    $(function() {
                        $("#layersTree").treegrid({
                            data: {{Js::from($data)}},
                            fit: true,
                            showHeader: false,
                            rownumbers: false,
                            idField: "id",
                            treeField: "text",
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "text",
                                    width: "100%",
                                }
                            ]],
                            onClickRow: (row) => {
                                if (row.type === "layer") {
                                    htmx.ajax("GET", `/layers/layer/${row.id}/content`, "#editArea");
                                }
                                if (row.type === "genericlabel") {
                                    htmx.ajax("GET", `/layers/genericlabel/${row.id}/content`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
