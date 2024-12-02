<div id="frameReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start">
        <div class="col-12 sm:col-12 md:col-12 lg:col-7 xl:col-6">
            <h1>
                <x-element.frame name="{{$construction->name}}"></x-element.frame>
            </h1>
        </div>
        <div
            class="col-12 sm:col-12 md:col-12 lg:col-5 xl:col-6 flex gap-1 flex-wrap align-items-center justify-content-end">
            <div class="ui label wt-tag-id">
                #{{$construction->idConstruction}}
            </div>
            <button
                id="btnDownload"
                class="ui button mini basic secondary"
            ><i class="icon material">download</i>PDF
            </button>
        </div>
    </div>
    <x-card title="Definition" class="frameReport__card frameReport__card--main">
        {!! str_replace('ex>','code>',nl2br($construction->description)) !!}
    </x-card>
    <x-card title="Construction Elements" class="frameReport__card frameReport__card--main">
        <table class="ui celled striped table">
            <tbody>
            {{--            @foreach ($ce as $ceObj)--}}
            {{--                <tr>--}}
            {{--                    <td class="collapsing">--}}
            {{--                        <span class="color_{{$ceObj->idColor}}">{{$ceObj->name}}</span>--}}
            {{--                    </td>--}}
            {{--                    <td class="pl-2">{!! $ceObj->description !!}</td>--}}
            {{--                    <td>--}}
            {{--                        @foreach ($ceObj->relations as $relation)--}}
            {{--                            <b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}--}}
            {{--                        @endforeach--}}
            {{--                    </td>--}}
            {{--                    <td class="right aligned collapsing">--}}
            {{--                        {{$ce['semanticTypes'][$ceObj->idFrameElement]->name ?? ''}}--}}
            {{--                    </td>--}}
            {{--                </tr>--}}
            {{--            @endforeach--}}
            </tbody>
        </table>
    </x-card>
    <x-card title="Relations" class="frameReport__card frameReport__card--main" open="true">
        @php($i = 0)
        @foreach ($relations as $nameEntry => $relations1)
            @php([$entry, $name] = explode('|', $nameEntry))
            @php($relId = str_replace(' ', '_', $name))
            <x-card-plain
                title="<span class='color_{{$entry}}'>{{$name}}</span>"
                @class(["frameReport__card" => (++$i < count($report['relations']))])
                class="frameReport__card--internal"
            >
                <div class="flex flex-wrap gap-1">
                    @foreach ($relations1 as $idConstruction => $relation)
                        <button
                            id="btnRelation_{{$relId}}_{{$idConstruction}}"
                            class="ui button basic"
                        >
                            <a
                                href="/report/cxn/{{$idConstruction}}"
                            >
                                <x-element.construction name="{{$relation['name']}}"></x-element.construction>
                            </a>
                        </button>
                    @endforeach
                </div>
            </x-card-plain>
        @endforeach
        <x-card-plain
            title="Comparative concepts"
            class="frameReport__card--internal"
            open="true"
        >
            @php($i = 0)
            @foreach ($concepts as $concept)
                <button
                    id="btnRelation_concept_{{$concept->idConcept}}"
                    class="ui button basic"
                >
                    <a
                        href="/report/c5/{{$concept->idConcept}}"
                    >
                        <x-element.concept name="{{$concept->name}}"></x-element.concept>
                    </a>
                </button>
            @endforeach
        </x-card-plain>

        <x-card-plain
            title="Evokes"
            class="frameReport__card--internal"
            open="true"
        >
            @php($i = 0)
            @foreach ($evokes as $frame)
                <button
                    id="btnRelation_evokes_{{$frame->idFrame}}"
                    class="ui button basic"
                >
                    <a
                        href="/report/frame/{{$frame->idFrame}}"
                    >
                        <x-element.frame name="{{$frame->name}}"></x-element.frame>
                    </a>
                </button>
            @endforeach
        </x-card-plain>

    </x-card>

</div>
<script>
    $("#btnDownload").click(function(e) {
        const options = {
            margin: 0.5,
            filename: '{{$construction->name}}.pdf',
            image: {
                type: "jpeg",
                quality: 500
            },
            html2canvas: {
                scale: 1
            },
            jsPDF: {
                unit: "in",
                format: "a4",
                orientation: "portrait"
            }
        };

        e.preventDefault();
        const element = document.getElementById("frameReport");
        html2pdf().from(element).set(options).save();
    });
</script>
