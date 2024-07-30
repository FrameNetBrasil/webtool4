<x-layout.full>
    <x-slot:header>
        <h1>
            Frame Grapher
        </h1>
    </x-slot:header>
    <x-slot:main>
        <x-layout.grapher>
            <x-slot:menu>
                <form>
                    <div class="flex flex-row align-content-start gap-2 pl-2">
                        <x-combobox.frame
                            id="idFrame"
                            label=""
                            placeholder="Frame (min: 2 chars)"
                            style="width:250px"
                        ></x-combobox.frame>
                        <x-combobox.panel
                            id="frameRelations"
                            label="Show relations" width="250"
                        >
                            @foreach($relations as $i => $relation)
                                <div>
                                    <input type="checkbox" checked name="idRelationType[{{$i}}]"
                                           value="{{$relation['value']}}">
                                    <span
                                        class="color_{{$relation['entry']}}">{{$relation['name']}}
                                </span>
                                </div>
                            @endforeach
                        </x-combobox.panel>
                        <x-button
                            id="btnSubmit"
                            label="Submit"
                            hx-target="#frameGraph"
                            hx-post="/grapher/frame/graph"
                        ></x-button>
                        <x-button
                            id="btnClear"
                            label="Clear"
                            color="secondary"
                            hx-target="#frameGraph"
                            hx-post="/grapher/frame/graph/0"
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
        <div id="frameGraph" hx-trigger="load" hx-post="/grapher/frame/graph">
        </div>
    </x-slot:main>
</x-layout.full>
