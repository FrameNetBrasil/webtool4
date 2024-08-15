<x-form id="formNewQualiaLU" title="New QualiaLU" :center="false"  hx-post="/tqr2/qualialu/new" hx-target="#editArea">
    <x-slot:fields>
        <x-combobox.qualiastructure
            id="idQualiaStructure"
            label="QualiaStructure (min 3 chars)"
            placeholder=""
            onSelect="console.log(result);"
        ></x-combobox.qualiastructure>
        <hr>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
