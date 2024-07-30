<x-layout.full>
    <x-slot:header>
        <h1>
            Domain Grapher
        </h1>
    </x-slot:header>
    <x-slot:main>
        <x-layout.grapher>
            <x-slot:menu>
                <form>
                    <div class="flex flex-row align-content-start gap-2 pl-2">
                        <x-combobox.frame-domain
                            id="idSemanticType"
                            label=""
                            placeholder="Domain"
                            value=""
                            style="width:250px"
                        ></x-combobox.frame-domain>
                        <x-combobox.panel
                            id="frameRelations"
                            label="Show relations"
                            width="250"
                        >
                            @foreach($relations as $i => $relation)
                                <div>
                                    <input
                                        type="checkbox" checked
                                        name="idRelationType[{{$i}}]"
                                        value="{{$relation['value']}}"
                                    ><span
                                        class="color_{{$relation['entry']}}">{{$relation['name']}}</span>
                                </div>
                            @endforeach
                        </x-combobox.panel>
                        <x-button
                            id="btnSubmit"
                            label="Submit"
                            hx-target="#domainGraph"
                            hx-post="/grapher/domain/graph"
                        ></x-button>
                        <x-button
                            id="btnClear"
                            label="Clear"
                            color="secondary"
                            hx-target="#domainGraph"
                            hx-post="/grapher/domain/graph/0"
                        ></x-button>
                        <hx-disclosure
                            aria-controls="graph-drawer"
                            class="hxBtn">
                            Graph Options
                        </hx-disclosure>
                    </div>
                </form>
            </x-slot:menu>
        </x-layout.grapher>
        <div id="domainGraph" hx-trigger="load" hx-post="/grapher/domain/graph">
        </div>
    </x-slot:main>
</x-layout.full>
