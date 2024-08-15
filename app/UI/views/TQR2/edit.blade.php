<x-layout.crud>
    <x-slot:name>
        <span class="color_frame">{{$frame->name}}</span>
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
                            <x-text-field
                                label="Type"
                                id="type"
                                value=""
                            ></x-text-field>
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                        </div>
                    </div>
                </x-slot:fields>
                <x-slot:buttons>
                    <x-submit label="Add" hx-post="/tqr2/argument/new"></x-submit>
                </x-slot:buttons>
            </x-form>
            <h2>Arguments</h2>
            {{--    @include("Lexicon.lexemeentries")--}}
        </div>
    </x-slot:main>
</x-layout.crud>
