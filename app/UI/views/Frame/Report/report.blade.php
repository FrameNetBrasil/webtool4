<div id="frameReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start">
        <div class="col-12 sm:col-12 md:col-12 lg:col-7 xl:col-6">
            <h1>
                <x-element.frame name="{{$frame->name}}"></x-element.frame>
            </h1>
        </div>
        <div
            class="col-12 sm:col-12 md:col-12 lg:col-5 xl:col-6 flex gap-1 flex-wrap align-items-center justify-content-end">
            @foreach ($classification as $name => $values)
                @foreach ($values as $value)
                    <div
                        class="sm:pb-1"
                    >
                        <div class="ui label wt-tag-{{$name}}">
                            {{$value}}
                        </div>
                    </div>
                @endforeach
            @endforeach
            {{--            <i id="btnDownload" class="icon material text-2xl cursor-pointer" title="Save as PDF">picture_as_pdf</i>--}}
            <button
                id="btnDownload"
                class="ui button mini basic secondary"
            ><i class="icon material">download</i>PDF
            </button>
        </div>
    </div>
    <x-card title="Definition" class="frameReport__card frameReport__card--main">
        {!! str_replace('ex>','code>',nl2br($frame->description)) !!}
    </x-card>
    <x-card title="Frame Elements" class="frameReport__card frameReport__card--main">
        <table class="ui celled striped table">
            <thead>
            <tr>
                <th colspan="4">Core</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($fe['core'] as $feObj)
                <tr>
                    <td class="collapsing">
                        <span class="color_{{$feObj->idColor}}">{{$feObj->name}}</span>
                    </td>
                    <td class="pl-2">{!! $feObj->description !!}</td>
                    <td>
                        @foreach ($feObj->relations as $relation)
                            <b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                        @endforeach
                    </td>
                    <td class="right aligned collapsing">
                        {{$fe['semanticTypes'][$feObj->idFrameElement]->name ?? ''}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if ($fe['core_unexpressed'])
            <table class="ui celled striped table">
                <thead>
                <tr>
                    <th colspan="4">Core-Unexpressed</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($fe['core_unexpressed'] as $feObj)
                    <tr>
                        <td class="collapsing">
                            <span class="color_{{$feObj->idColor}}">{{$feObj->name}}</span>
                        </td>
                        <td class="pl-2">{!! $feObj->description !!}</td>
                        <td>
                            @foreach ($feObj->relations as $relation)
                                <b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                            @endforeach
                        </td>
                        <td class="right aligned collapsing">
                            {{$fe['semanticTypes'][$feObj->idFrameElement]->name ?? ''}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @if ($fecoreset)
            <x-card title="FE Core set(s)" class="frameReport__card frameReport__card--internal">
                <table id="feCoreSet" class="frameReport__table">
                    <tbody>
                    <tr>
                        <td class="pl-2">{{$fecoreset}}</td>
                    </tr>
                    </tbody>
                </table>
            </x-card>
        @endif
        @if ($fe['peripheral'])
            <table class="ui celled striped table">
                <thead>
                <tr>
                    <th colspan="4">Peripheral</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($fe['peripheral'] as $feObj)
                    <tr>
                        <td class="collapsing">
                            <span class="color_{{$feObj->idColor}}">{{$feObj->name}}</span>
                        </td>
                        <td class="pl-2">{!! $feObj->description !!}</td>
                        <td>
                            @foreach ($feObj->relations as $relation)
                                <b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                            @endforeach
                        </td>
                        <td class="right aligned collapsing">
                            {{$fe['semanticTypes'][$feObj->idFrameElement]->name ?? ''}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @if ($fe['extra_thematic'])
            <table class="ui celled striped table">
                <thead>
                <tr>
                    <th colspan="4">Extra-thematic</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($fe['extra_thematic'] as $feObj)
                    <tr>
                        <td class="collapsing">
                            <span class="color_{{$feObj->idColor}}">{{$feObj->name}}</span>
                        </td>
                        <td class="pl-2">{!! $feObj->description !!}</td>
                        <td>
                            @foreach ($feObj->relations as $relation)
                                <b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                            @endforeach
                        </td>
                        <td class="right aligned collapsing">
                            {{$fe['semanticTypes'][$feObj->idFrameElement]->name ?? ''}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </x-card>
    <x-card title="Frame-Frame Relations" class="frameReport__card frameReport__card--main" open="true">
        @php($i = 0)
        @foreach ($relations as $nameEntry => $relations1)
            @php([$entry, $name] = explode('|', $nameEntry))
            @php($relId = str_replace(' ', '_', $name))
            <x-card-plain
                title="<span class='color_{{$entry}}'>{{$name}}</span>"
                @class(["frameReport__card" => (++$i < count($report['relations']))])
                class="frameReport__card--internal">
                <div class="flex flex-wrap gap-1">
                    @foreach ($relations1 as $idFrame => $relation)
                        <button
                            id="btnRelation_{{$relId}}_{{$idFrame}}"
                            class="ui button basic"
                        >
                            <a
                                href="/report/frame/{{$idFrame}}"
                            >
                                <x-element.frame name="{{$relation['name']}}"></x-element.frame>
                            </a>
                        </button>
                    @endforeach
                </div>
            </x-card-plain>
        @endforeach
    </x-card>
    <x-card title="Lexical Units" class="frameReport__card frameReport__card--main" open="true">
        @foreach ($lus as $POS => $posLU)
            <x-card-plain
                title="POS: {{$POS}}"
                class="frameReport__card--internal"
            >
                <div class="flex flex-wrap gap-1">
                    @foreach ($posLU as $lu)
                        <button
                            id="btnLU{{$lu->idLU}}"

                            class="ui button basic"
                        ><a href="/report/lu/{{$lu->idLU}}">{{$lu->name}}</a></button>
                    @endforeach
                </div>
            </x-card-plain>
        @endforeach
    </x-card>
</div>
<script>
    $("#btnDownload").click(function(e) {
        const options = {
            margin: 0.5,
            filename: '{{$frame->name}}.pdf',
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
