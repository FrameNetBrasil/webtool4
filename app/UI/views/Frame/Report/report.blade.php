<div id="frameReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start h-3rem">
        <div class="col">
            <h1><x-element.frame name="{{$frame->name}}"></x-element.frame></h1>
        </div>
        <div class="col text-right">
            @foreach ($classification as $name => $values)
                @foreach ($values as $value)
                    <div class="ui label tag wt-tag-{{$name}}">
                        {{$value}}
                    </div>
                @endforeach
            @endforeach
                <i id="btnDownload" class="icon material text-lg cursor-pointer" title="Save as PDF">picture_as_pdf</i>
        </div>
    </div>
    <x-card title="Definition" class="frameReport__card frameReport__card--main">
        {!! str_replace('ex>','code>',nl2br($frame->description)) !!}
    </x-card>
    <x-card title="Frame Elements" class="frameReport__card frameReport__card--main">
        <x-card title="Core" class="frameReport__card  frameReport__card--internal">
            <table id="feNuclear" class="frameReport__table">
                <tbody>
                @foreach ($fe['core'] as $feCore)
                    <tr>
                        <td>
                            <span class="color_{{$feCore->idColor}}">{{$feCore->name}}</span>
                            @foreach ($feCore->relations as $relation)
                                <br><b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                            @endforeach
                        </td>
                        <td class="pl-2">{!! $feCore->description !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-card>
        @if ($fe['core_unexpressed'])
            <x-card title="Core-Unexpressed" class="frameReport__card frameReport__card--internal">
                <table id="feCoreUnexpressed" class="frameReport__table">
                    <tbody>
                    @foreach ($fe['core_unexpressed'] as $feCoreUn)
                        <tr>
                            <td>
                                <span class="color_{{$feCoreUn->idColor}}">{{$feCoreUn->name}}</span>
                                @foreach ($feCoreUn->relations as $relation)
                                    <br><b>{{$relation['name']}}
                                        :&nbsp;</b>{{$relation['relatedFEName']}}
                                @endforeach
                            </td>
                            <td class="pl-2">{!! $feCoreUn->description !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-card>
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

        <x-card title="Non-Core" class="frameReport__card frameReport__card--internal">
            <table id="feNonNuclear" class="frameReport__table">
                <tbody>
                @foreach ($fe['noncore'] as $feNN)
                    <tr>
                        <td>
                            <span class="color_{{$feNN->idColor}}">{{$feNN->name}}</span>
                            @foreach ($feNN->relations as $relation)
                                <br><b>{{$relation['name']}}:&nbsp;</b>{{$relation['relatedFEName']}}
                            @endforeach
                        </td>
                        <td class="pl-2">{!! $feNN->description !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-card>
    </x-card>
    <x-card title="Frame-Frame Relations" class="frameReport__card frameReport__card--main">
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
                        ><x-element.frame name="{{$relation['name']}}"></x-element.frame>
                        </a>
                    </button>
                @endforeach
                </div>
            </x-card-plain>
        @endforeach
    </x-card>
    <x-card title="Lexical Units" class="frameReport__card frameReport__card--main">
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
