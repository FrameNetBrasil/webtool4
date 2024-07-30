<div id="luReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start h-3rem">
        <div class="col">
            <h1><x-element.lu frame="{{$lu->frameName}}" name="{{$lu->name}}"></x-element.lu></h1>
        </div>
        <div class="col text-right">
                    <div class="ui label tag wt-tag-en">
                        {{$language->language}}
                    </div>
            <div class="ui label tag wt-tag-id">
                #{{$lu->idLU}}
            </div>
        </div>
    </div>
    <x-card title="Definition" class="luReport__card">
        {!! $lu->senseDescription !!}
        @if(isset($incorporatedFE))
            <hr>
            Incorporated FE: <x-element.fe name="{{$incorporatedFE->name}}" type="{{$incorporatedFE->coreType}}" idColor="{{$incorporatedFE->idColor}}"></x-element.fe>
        @endif
    </x-card>
    <div class="grid overflow-y-auto h-25rem w-full mt-2 mb-2">
        <div class="col-4">
            <x-datagrid
                id="gridFE"
                title="FE Syntatic Realizations"
            >
                <x-slot:thead>
                    <thead>
                    <th>FE</th>
                    <th style="width:8rem"># Annotated</th>
                    <th>Realization(s)</th>
                    </thead>
                </x-slot:thead>
                @foreach($realizations as $feIdEntity => $gfptas)
                    @if($feIdEntity)
                    <tr
                        style="display:table;table-layout: fixed;"
                    >
                        <td>
                            <x-element.fe name="{{$fes[$feIdEntity]['name']}}"
                                          type="{{$fes[$feIdEntity]['type']}}"
                                          idColor="{{$fes[$feIdEntity]['idColor']}}"></x-element.fe>
                        </td>
                        <td style="width:8rem;text-align:center">
                            <a href="#" onclick="reportLU.addFESentences('{{$feIdEntity}}')">
                                {!! count($fes[$feIdEntity]['as']) !!}
                            </a>
                        </td>
                        <td>
                            @foreach($gfptas as $gf => $ptas)
                                @foreach($ptas as $pt => $idRealization)
                                    {{$gf}}.{{$pt}}&nbsp;&nbsp;
                                    <a href="#"
                                       onclick="reportLU.addASSentences(reportLU.realizationAS['{{$idRealization[0]}}'])">
                                        ({!! count($realizationAS[$idRealization[0]]) !!})
                                    </a>
                                    <br />
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                    @endif
                @endforeach
            </x-datagrid>
        </div>
        <div class="col-8" id="colGridValence">
            <x-datagrid
                id="gridValence"
                title="Valence Patterns"
                class="gridValence"
            >
                <x-slot:thead>
                    <thead>
                    <th style="width:8rem"># Annotated</th>
                    <th colspan="{{$maxCountFE}}" class="text-center">Patterns</th>
                    </thead>
                </x-slot:thead>
                @php($p = 0)
                @foreach($vp as $idVPFE => $vp1)
                    @php($l = 0)
                    @foreach($patterns[$idVPFE] as $idVP => $scfegfptas)
                        @if ($l++ == 0)
                            <tr class="{!! ($p > 0) ? 'pattern' : '' !!}">
                                <td style="width:8rem;text-align:center">
                                    <a href="#"
                                       onclick="reportLU.addASSentences(reportLU.patternFEAS['{{$idVPFE}}'])">
                                        {{$vpfe[$idVPFE]['count']}}
                                    </a>
                                </td>
                                @php($i = 0)
                                @foreach($scfegfptas as $sc => $fegfptas)
                                    @foreach($fegfptas as $feIdEntity => $gfptas)
                                        @if($feIdEntity)
                                        @foreach($gfptas as $gf => $ptas)
                                            @foreach($ptas as $pt => $as)
                                                @php($i = $i + 1)
                                                <td>
                                                    <x-element.fe name="{{$fes[$feIdEntity]['name']}}"
                                                                  type="{{$fes[$feIdEntity]['type']}}"
                                                                  idColor="{{$fes[$feIdEntity]['idColor']}}"></x-element.fe>
                                                </td>
                                            @endforeach
                                        @endforeach
                                        @endif
                                    @endforeach
                                @endforeach
                                @for($j = $i; $j < ($maxCountFE); $j++)
                                    <td></td>
                                @endfor
                            </tr>
                        @endif
                        <tr>
                            <td style="width:8rem;text-align:center">
                                <a href="#" onclick="reportLU.addASSentences(reportLU.patternAS['{{$idVP}}'])">
                                    {!! count($vp[$idVPFE][$idVP]) !!}
                                </a>
                            </td>
                            @php($i = 0)
                            @foreach($scfegfptas as $sc => $fegfptas)
                                @foreach($fegfptas as $fe => $gfptas)
                                    @foreach($gfptas as $gf => $ptas)
                                        @foreach($ptas as $pt => $as)
                                            @php($i = $i + 1)
                                            <td>{{$gf}}<br />{{$pt}}</td>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                            @for($j = $i; $j < ($maxCountFE ); $j++)
                                <td></td>
                            @endfor
                        </tr>
                    @endforeach
                    @php(++$p)
                @endforeach
            </x-datagrid>
        </div>
    </div>
    <div id="reportLUSentences" class="mt-2 flex-grow-0  h-25rem overflow-y-auto">
        <div class="flex flex-column">
            <div class="text">
                <x-button color="secondary" onclick="reportLU.clearSentences()">Clear Sentences</x-button>
                <x-button color="secondary" onclick="reportLU.toogleSentenceColors()">Turn Colors On/Off</x-button>
            </div>
            <div id="placeholder" class="ui placeholder mt-2" style="display:none">
                <div class="paragraph">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
            <div id="divLexicalEntrySentences">
                <div id="divSentencesColorOn" style="display:block">
                </div>
                <div id="divSentencesColorOff" style="display:none">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btnDownload").click(function(e) {
        const options = {
            margin: 0.5,
            filename: '{{$lu->name}}.pdf',
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
        const element = document.getElementById("colGridValence");
        html2pdf().from(element).set(options).save();
    });

    reportLU = {
        fes: {{ Js::from($fes) }},
        realizationAS: {{ Js::from($realizationAS) }},
        feAS: {{ Js::from($feAS) }},
        patternFEAS: {{ Js::from($patternFEAS) }},
        patternAS: {{ Js::from($patternAS) }},
        showFrame: function(idFrame) {
            $("#reportLUCenterPane").html("");
            manager.doGet("/report/frame/showFrame" + "/" + {{$lu->idFrame}}, "reportLUCenterPane");
        },
        clearSentences: function() {
            $("#divSentencesColorOn").html("");
            $("#divSentencesColorOff").html("");
        },
        toogleSentenceColors: function() {
            if ($("#divSentencesColorOn").css("display") == "block") {
                $("#divSentencesColorOn").css("display", "none");
                $("#divSentencesColorOff").css("display", "block");
            } else {
                $("#divSentencesColorOn").css("display", "block");
                $("#divSentencesColorOff").css("display", "none");
            }
        },
        addFESentences: async function(feEntry) {
            $('#placeholder').show();
            await $.ajax({
                url: "/report/lu/sentences",
                method: "POST",
                dataType: "json",
                data: {
                    idAS: reportLU.feAS[feEntry],
                    _token: "{{ csrf_token() }}"
                },
                success: (sentences) => {
                    $('#placeholder').hide();
                    $.each(sentences, function(index, sentence) {
                        console.log(sentence);
                        var id = "sentence" + sentence.idSentence;
                        if ($("#" + id).length) {
                            //$(sentence.text).replaceAll('#' + id);
                        } else {
                            reportLU.addSentence(id, sentence.text, sentence.clean, sentence.idSentence);
                        }
                    });
                }
            });
        },
        addASSentences: async function(asSet) {
            $('#placeholder').show();
            await $.ajax({
                url: "/report/lu/sentences",
                method: "POST",
                dataType: "json",
                data: {
                    idAS: asSet,
                    _token: "{{ csrf_token() }}"
                },
                success: (sentences) => {
                    $('#placeholder').hide();
                    $.each(sentences, function(index, sentence) {
                        console.log(sentence);
                        var id = "sentence" + sentence.idSentence;
                        if ($("#" + id).length) {
                            //$(sentence.text).replaceAll('#' + id);
                        } else {
                            reportLU.addSentence(id, sentence.text, sentence.clean, sentence.idDocumentSentence);
                        }
                    });
                }
            });
        },
        addSentence: function(id, text, cleanText, idSentence) {
            var ban = `
                       <span class="delete material-icons-outlined wt-datagrid-icon wt-icon-delete" title="delete Sentence" onclick="reportLU.removeSentence('${id}')"></span>
                    `;
            $("#divSentencesColorOn").append("<div class='sentence' id='" + id + "'>" + ban + text + "  [#" + idSentence + "]</div>");
            $("#divSentencesColorOff").append("<div class='sentence' id='" + id + "'>" + ban + cleanText + "  [#" + idSentence + "]</div>");
        },
        removeSentence: function(id) {
            console.log(id);
            $("#" + id).remove();
        }
    };

</script>

