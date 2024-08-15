@php
    use App\Database\Criteria;use App\Services\AppService;
    $frameIcon = view('components.icon.frame')->render();
    $structureIcon = view('components.icon.document')->render();
    $frames = Criteria::table("view_frame as f")
        ->join("view_qualiastructure as qs","f.idFrame","=","qs.idFrame")
        ->where("f.name","startswith", $search->frame)
        ->where("f.idLanguage", "=", AppService::getCurrentIdLanguage())
        ->select("qs.idQualiaStructure","f.idFrame","f.name as frame")
      ->orderBy("f.name")
      ->get()->keyBy("idFrame")->all();
    $ids = array_keys($frames);
    $structures = Criteria::byFilterLanguage("view_qualiastructure",[
            "idFrame","IN", $ids
            ])->orderBy("idQualiaStructure")
            ->get()->groupBy("idFrame")
            ->toArray();
        $data = [];
        foreach($frames as $f) {
           $children = array_map(fn($item) => [
             'id'=> $item->idQualiaStructure,
             'text' => $structureIcon . $item->relation . "_" . $item->idQualiaStructure,
             'state' => 'closed',
             'type' => 'structure'
            ], $structures[$f->idFrame] ?? []);
            $data[] = [
                'id' => $f->idFrame,
                'text' => $frameIcon . $f->frame,
                'state' => 'closed',
                'type' => 'frame',
                'children' => $children
            ];
        }
@endphp
<div
    class="wt-datagrid flex flex-column h-full"
    hx-trigger="reload-gridTQR2 from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/tqr2/grid"
>
    <div class="datagrid-header-search flex">
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="frame"
                placeholder="Search Frame"
                hx-post="/tqr2/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#tqr2TreeWrapper"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
    </div>
    <div id="tqr2TreeWrapper">
        @fragment('search')
            <ul id="tqr2Tree" class="wt-treegrid">
            </ul>
            <script>
                $(function() {
                    $("#tqr2Tree").tree({
                        data: {{Js::from($data)}},
                        onClick: function(node) {
                            if (node.type === 'frame') {
                                $("#tqr2Tree").tree('toggle', node.target);
                            }
                            if (node.type === "structure") {
                                htmx.ajax("GET", `/tqr2/${node.id}/edit`, "#editArea");
                            }
                        }
                    });
                });
            </script>
        @endfragment
    </div>
</div>
