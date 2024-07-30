<x-layout.content>
    <div id="feInternalFormNew">
        @fragment('form')
            <x-form
                id="frameFERelationFormNew"
                title=""
                center="true"
                hx-post="/relation/feinternal"
                hx-target="#feInternalFormNew"
            >
                <x-slot:fields>
                    <x-hidden-field
                        id="idFrame"
                        :value="$idFrame"
                    ></x-hidden-field>
                    <x-checkbox.fe-frame
                        id="idFrameElementRelated"
                        :idFrame="$idFrame"
                        label="Frame Elements"
                        :value="$idFrameElementRelated ?? []"
                    ></x-checkbox.fe-frame>
                    <x-combobox.relation
                        id="relationType"
                        group="fe"
                        :value="$relationType ?? null"
                    ></x-combobox.relation>
                </x-slot:fields>
                <x-slot:buttons>
                    <x-submit
                        label="Add Relation"
                    ></x-submit>
                </x-slot:buttons>
            </x-form>
        @endfragment
    </div>
</x-layout.content>
