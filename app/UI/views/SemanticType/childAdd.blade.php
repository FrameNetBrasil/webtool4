<x-layout.content>
    <x-inline-form id="formAdd" title="Add SemanticType" center="true">
        <x-slot:fields>
            <x-hidden-field
                id="idEntity"
                :value="$idEntity"
            ></x-hidden-field>
            <x-combobox.semantic-type
                id="idSemanticType"
                label="Semantic Type"
                :root="$root"
                class="w-25rem"
            ></x-combobox.semantic-type>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit
                label="Add Semantic Type"
                hx-post="/semanticType/{{$idEntity}}/add"
            ></x-submit>
        </x-slot:buttons>
    </x-inline-form>
</x-layout.content>
