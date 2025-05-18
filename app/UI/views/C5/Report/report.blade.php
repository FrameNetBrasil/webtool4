<div id="frameReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start">
        <div class="col-12 sm:col-12 md:col-12 lg:col-7 xl:col-6">
            <h1>
                <x-element.concept name="{{$concept->name}} ({{$concept->type}})"></x-element.concept>
            </h1>
        </div>
        <div
            class="col-12 sm:col-12 md:col-12 lg:col-5 xl:col-6 flex gap-1 flex-wrap align-items-center justify-content-end">
            <div class="ui label wt-tag-id">
                #{{$concept->idConcept}}
            </div>
        </div>
    </div>
    <x-card title="Definition" class="frameReport__card frameReport__card--main">
        {!! str_replace('ex>','code>',nl2br($concept->description)) !!}
    </x-card>
    @if(!empty($constituents))
        <x-card title="Constituents" class="frameReport__card frameReport__card--main" open="true">
            @php($i = 0)
            @foreach ($constituents as $nameEntry => $relations1)
                @php([$entry, $name, $color] = explode('|', $nameEntry))
                @php($relId = str_replace(' ', '_', $name))
                <x-card-plain
                    title="<span style='color:{{$color}}'>{{$name}}</span>"
                    @class(["frameReport__card" => (++$i < count($report['relations']))])
                    class="frameReport__card--internal">
                    <div class="flex flex-wrap gap-1">
                        @foreach ($relations1 as $idConcept => $relation)
                            <button
                                id="btnRelation_{{$relId}}_{{$idConcept}}"
                                class="ui button basic"
                            >
                                <a
                                    href="/report/c5/{{$idConcept}}"
                                >
                                    @if ($relation['type'] == "inf")
                                        <x-element.concept_inf name="{{$relation['name']}}"></x-element.concept_inf>
                                    @elseif ($relation['type'] == "sem")
                                        <x-element.concept_sem name="{{$relation['name']}}"></x-element.concept_sem>
                                    @elseif ($relation['type'] == "cxn")
                                        <x-element.concept_cxn name="{{$relation['name']}}"></x-element.concept_cxn>
                                    @elseif ($relation['type'] == "str")
                                        <x-element.concept_str name="{{$relation['name']}}"></x-element.concept_str>
                                    @elseif ($relation['type'] == "def")
                                        <x-element.concept_def name="{{$relation['name']}}"></x-element.concept_def>
                                    @endif
                                </a>
                            </button>
                        @endforeach
                    </div>
                </x-card-plain>
            @endforeach
        </x-card>
    @endif
    @if(!empty($relations))
        <x-card title="Relations" class="frameReport__card frameReport__card--main" open="true">
            @php($i = 0)
            @foreach ($relations as $nameEntry => $relations1)
                @php([$entry, $name, $color] = explode('|', $nameEntry))
                @php($relId = str_replace(' ', '_', $name))
                <x-card-plain
                    title="<span style='color:{{$color}}'>{{$name}}</span>"
                    @class(["frameReport__card" => (++$i < count($report['relations']))])
                    class="frameReport__card--internal">
                    <div class="flex flex-wrap gap-1">
                        @foreach ($relations1 as $idConcept => $relation)
                            <button
                                id="btnRelation_{{$relId}}_{{$idConcept}}"
                                class="ui button basic"
                            >
                                <a
                                    href="/report/c5/{{$idConcept}}"
                                >
                                    @if ($relation['type'] == "inf")
                                        <x-element.concept_inf name="{{$relation['name']}}"></x-element.concept_inf>
                                    @elseif ($relation['type'] == "sem")
                                        <x-element.concept_sem name="{{$relation['name']}}"></x-element.concept_sem>
                                    @elseif ($relation['type'] == "cxn")
                                        <x-element.concept_cxn name="{{$relation['name']}}"></x-element.concept_cxn>
                                    @elseif ($relation['type'] == "str")
                                        <x-element.concept_str name="{{$relation['name']}}"></x-element.concept_str>
                                    @elseif ($relation['type'] == "def")
                                        <x-element.concept_def name="{{$relation['name']}}"></x-element.concept_def>
                                    @endif
                                </a>
                            </button>
                        @endforeach
                    </div>
                </x-card-plain>
            @endforeach
        </x-card>
    @endif
</div>
