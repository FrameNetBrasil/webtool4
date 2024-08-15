<x-layout.crud>
    <x-slot:name>
        <span class="color_frame">{{$frame->name}}</span>::{{$relation->name}}_{{$structure->idQualiaStructure}}
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$structure->idQualiaStructure}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Structure '{{$structure->idQualiaStructure}}'.`, '/tqr2/{{$structure->idQualiaStructure}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
    </x-slot:nav>
    <x-slot:main>
        <div id="structureEditWrapper">
            <x-form
                id="argumentFormAdd"
                title="Add Argument"
                center="true"
            >
                <x-slot:fields>
                    <x-hidden-field id="idQualiaStructure" :value="$structure->idQualiaStructure"></x-hidden-field>
                    <div class="grid">
                        <div class="col">
                            <x-combobox.fe-frame
                                id="idFrameElement"
                                label="FE"
                                :idFrame="$structure->idFrame"
                                :coreType="['cty_core','cty_core-unexpressed']"
                                class="w-25rem"
                            ></x-combobox.fe-frame>
                        </div>
                        <div class="col">
                            <x-combobox.options
                                label="Type"
                                id="type"
                                value=""
                                :options="$types"
                                class="w-10rem"
                            ></x-combobox.options>
                        </div>
                    </div>
                </x-slot:fields>
                <x-slot:buttons>
                    <x-submit label="Add" hx-post="/tqr2/argument/new"></x-submit>
                </x-slot:buttons>
            </x-form>
            <h2>Arguments</h2>
                @include("TQR2.arguments")
        </div>
    </x-slot:main>
</x-layout.crud>
