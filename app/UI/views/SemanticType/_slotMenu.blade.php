<x-link-button
    id="menuEntries"
    label="Translations"
    hx-get="/frames/{{$data->frame->idFrame}}/entries"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuFE"
    label="FrameElements"
    hx-get="/frames/{{$data->frame->idFrame}}/fes"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuNewLU"
    label="LUs"
    hx-get="/frames/{{$data->frame->idFrame}}/lus"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuClassification"
    label="Classification"
    hx-get="/frames/{{$data->frame->idFrame}}/classification"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuRelations"
    label="Relations"
    hx-get="/frames/{{$data->frame->idFrame}}/relations"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuFERelations"
    label="FE-Relations"
    hx-get="/frames/{{$data->frame->idFrame}}/fes/relations"
    hx-target="#frameEditPane"
></x-link-button>
<x-link-button
    id="menuSemanticTypes"
    label="SemanticTypes"
    hx-get="/frames/{{$data->frame->idFrame}}/semanticTypes"
    hx-target="#frameEditPane"
></x-link-button>