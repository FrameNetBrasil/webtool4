<div
    class="card-grid dense pt-2"
    hx-trigger="reload-gridLayerTypes from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/layers/{{$idLayerGroup}}/layertypes/grid"
>
    @foreach($layerTypes as $layerType)
        <div
            class="ui card option-card cursor-pointer"
            onclick="window.location.assign('/layertype/{{$layerType->idLayerType}}/edit')"
        >
            <div class="content overflow-hidden">
                <span class="right floated">
                    <x-ui::delete
                         title="remove LayerType from LayerGroup"
                         onclick="event.stopPropagation(); messenger.confirmDelete(`Removing LayerType '{{$layerType->name}}' from LayerGroup.`, '/layers/{{$idLayerGroup}}/layertypes/{{$layerType->idLayerType}}')"
                    ></x-ui::delete>
                </span>
                <div class="header">
                    #{{$layerType->idLayerType}}
                </div>
                <div class="description">
                    <i class="layer group icon"></i>
                    {{$layerType->name}}
                </div>
                <div class="meta">
                    Order: {{$layerType->layerOrder}} |
                    @if($layerType->allowsApositional)
                        <i class="check icon green"></i> Apositional
                    @endif
                    @if($layerType->isAnnotation)
                        <i class="check icon blue"></i> Annotation
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
