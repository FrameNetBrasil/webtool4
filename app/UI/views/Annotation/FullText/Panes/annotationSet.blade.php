<div class="annotationSet">
    <div class="ui card w-full">
        <div class="content">
            <div class="header">
                <div class="grid">
                    <div class="col-8">
                        LU: {{$lu->frame->name}}.{{$lu->name}}
                    </div>
                    <div class="col-4 text-right">
                        <div class="ui dropdown alternativeLU">
                            <div class="text">Alternative LUs</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                @foreach($alternativeLU as $lu)
                                    <div class="item">{{$lu->frameName}}.{{$lu->lu}}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="ui label tag wt-tag-id">
                            #{{$idAnnotationSet}}
                        </div>
                        <button
                            class="ui button negative"
                            onclick="manager.confirmDelete(`Removing AnnotationSet #{{$idAnnotationSet}}'.`, '/annotation/fullText/annotationset/{{$idAnnotationSet}}', null, '#workArea')"
                        >
                            Delete this AnnotationSet
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="description">
                @include("Annotation.FullText.Panes.annotationSetAnnotations")
            </div>
        </div>
    </div>
</div>


<div class="ui secondary menu tabs">
    <a class="item" data-tab="fe">FE</a>
    <a class="item" data-tab="gf">GF</a>
    <a class="item" data-tab="pt">PT</a>
    <a class="item" data-tab="other">Other</a>
    <a class="item" data-tab="pos">{{$pos->POS}}</a>
    <a class="item" data-tab="sent">Sent</a>
</div>
<div class="gridLabels">
    <div class="labels">
        {{--            <div class="grids flex flex-column flex-grow-1">--}}
        @foreach($labels as $type => $labelData)
            <div class="ui card w-full tab {!! ($type == 'fe') ? 'active' : '' !!}" data-tab="{{$type}}">
                <div class="content">
                    <div class="rowFE">
                        @foreach($labelData as $idEntity => $label)
                            @php(debug($label))
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$label->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idEntity:{{$idEntity}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/label"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idEntity:{{$idEntity}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    @if ($type == 'fe')
                                        <x-element.fe
                                            name="{{$label->name}}"
                                            type="{{$label->coreType}}"
                                            idColor="{{$label->idColor}}"
                                        ></x-element.fe>
                                    @else
                                        <x-element.gl
                                            name="{{$label->name}}"
                                            idColor="{{$label->idColor}}"
                                        ></x-element.gl>
                                    @endif
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script type="text/javascript">
    console.log({{$idAnnotationSet}});
    Alpine.store('ftStore').idAnnotationSet = {{$idAnnotationSet}};
    Alpine.store('ftStore').updateASData();
    $(function() {
        $(".tabs .item")
            .tab()
        ;
        $(".alternativeLU")
            .dropdown({
                action: "hide"
            });
    });
</script>
