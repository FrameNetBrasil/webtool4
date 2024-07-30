<h2>FE-FE Relation for [<span class="color_frame">{{$frame->name}}</span> <span
        class='color_{{$relation->relationType}}'>{{$relation->name}}</span> <span class="color_frame">{{$relatedFrame->name}}]</span></h2>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/relations/{{$idEntityRelation}}/formNew"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/relations/{{$idEntityRelation}}/grid"
></div>
