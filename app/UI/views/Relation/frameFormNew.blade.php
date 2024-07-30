<x-layout.content>
    <x-inline-form
        id="frameRelationFormNew"
        title=""
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field id="idFrame" :value="$idFrame"></x-hidden-field>
            <x-combobox.relation
                id="relationType"
                group="frame"
            ></x-combobox.relation>
            <div class="w-25rem">
            <x-combobox.frame
                id="idFrameRelated"
                label="Related Frame [min: 3 chars]"
            ></x-combobox.frame>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/relation/frame"></x-submit>
        </x-slot:buttons>
    </x-inline-form>
</x-layout.content>
