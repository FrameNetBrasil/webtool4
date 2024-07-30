<x-layout.report>
    <x-slot:title>
        LU Report
    </x-slot:title>
    <x-slot:actions>
        <x-button
            href="/frame/{{$lu->idFrame}}"
            icon="frame__before"
            label="<span class='color_frame'>{!! $lu->frame->name !!}</span>"
            color="secondary"
        ></x-button>
        <x-button id="btnDownload" label="Save as PDF" color="secondary" class="m-1"></x-button>
        <x-button id="btnBack" label="Return to search" color="secondary" hx-get="/report/lu" hx-target="body"
                  class="m-1"></x-button>
    </x-slot:actions>
    <x-slot:name>
        <h1><span class="color_lexicon">{{$lu->name}}</span></h1>
    </x-slot:name>
    <x-slot:detail>
        <x-tag label="{{$language->language}}"></x-tag>
        <x-tag label="{{$lu->frame->name}}"></x-tag>
        <x-tag label="#{{$lu->idLU}}"></x-tag>
    </x-slot:detail>
    <x-slot:pane>
        <div id="luReport" class="flex flex-column">
            <div class="grid overflow-y-auto h-25rem w-full">
                <div class="col-12">
                    <x-card title="Definition" class="luReport__card">
                        {!! $lu->senseDescription !!}
                    </x-card>
                </div>
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
                        @endforeach
                    </x-datagrid>
                </div>
                <div class="col-8" id="colGridValence">
                    <x-datagrid
                        id="gridValence"
                        title="Valence Patterns"
                    >
                        <x-slot:thead>
                            <thead>
                            <th style="width:8rem"># Annotated</th>
                            <th colspan="{{$maxCountFE}}">Patterns</th>
                            </thead>
                        </x-slot:thead>
                        @foreach($vp as $idVPFE => $vp1)
                            @php($l = 0)
                            @foreach($patterns[$idVPFE] as $idVP => $scfegfptas)
                                @if ($l++ == 0)
                                    <tr class="pattern">
                                        <td style="width:8rem;text-align:center">
                                            <a href="#"
                                               onclick="reportLU.addASSentences(reportLU.patternFEAS['{{$idVPFE}}'])">
                                                {{$vpfe[$idVPFE]['count']}}
                                            </a>
                                        </td>
                                        @php($i = 0)
                                        @foreach($scfegfptas as $sc => $fegfptas)
                                            @foreach($fegfptas as $feIdEntity => $gfptas)
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
                        @endforeach
                    </x-datagrid>
                </div>
            </div>
            <div id="reportLUSentences" class="mt-2">
                <div class="text">
                    <x-button color="secondary" onclick="reportLU.clearSentences()">Clear Sentences</x-button>
                    <x-button color="secondary" onclick="reportLU.toogleSentenceColors()">Turn Colors On/Off</x-button>
                </div>
                <div id="divLexicalEntrySentences">
                    <div id="divSentencesColorOn" style="display:block">
                    </div>
                    <div id="divSentencesColorOff" style="display:none">
                    </div>
                </div>
            </div>
        </div>
        <script>
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

            $("#btnDownload").click(function(e) {
                e.preventDefault();
                const element = document.getElementById("colGridValence");
                html2pdf().from(element).set(options).save();
            });

            const reportLU = {
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
                    await $.ajax({
                        url: "/report/lu/sentences",
                        method: "POST",
                        dataType: "json",
                        data: {
                            idAS: reportLU.feAS[feEntry],
                            _token: "{{ csrf_token() }}"
                        },
                        success: (sentences) => {
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
                    await $.ajax({
                        url: "/report/lu/sentences",
                        method: "POST",
                        dataType: "json",
                        data: {
                            idAS: asSet,
                            _token: "{{ csrf_token() }}"
                        },
                        success: (sentences) => {
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
                addSentence: function(id, text, cleanText, idSentence) {
                    var ban = `
                       <span class="delete material-icons-outlined wt-datagrid-icon wt-icon-delete" title="delete Sentence" onclick="reportLU.removeSentence('${id}')"></span>
                    `;
                    $("#divSentencesColorOn").append("<div class='sentence' id='" + id + "'>" + ban + text + "  [#" + idSentence + "]</div>");
                    // var regex = /<span class="fe_([^"]*)"([^>]*)>([^<]*)<\/span>/g;
                    // wbText = text.replace(regex, "<span class=\"none\">[<sub>$1</sub>$3]<\/span>");
                    // wbText = wbText.replace(/(\[(<sub>target<\/sub>)([^\]]*)\])/g, function target(x0, x1, x2, x3) {
                    //     return x3.toUpperCase() + "<sup>Target</sup>";
                    // });
                    // for (fe in reportLU.fes) {
                    //     wbText = wbText.replace(new RegExp(fe, "g"), reportLU.fes[fe].name);
                    // }
                    $("#divSentencesColorOff").append("<div class='sentence' id='" + id + "'>" + ban + cleanText + "  [#" + idSentence + "]</div>");
                },
                removeSentence: function(id) {
                    console.log(id);
                    $("#" + id).remove();
                }
            };
            console.log(reportLU);
        </script>
    </x-slot:pane>
</x-layout.report>
