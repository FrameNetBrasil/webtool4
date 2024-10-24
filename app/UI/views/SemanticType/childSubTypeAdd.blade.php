<x-form>
    <x-slot:fields>
        <x-hidden-field
            id="idEntity"
            :value="$idEntity"
        ></x-hidden-field>
        <div class="field">
            <x-text-field
                id="semanticTypeName"
                label="English name"
                value=""
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit
            label="Add SubType"
            hx-post="/semanticType/{{$idEntity}}/addSubType"
        ></x-submit>
    </x-slot:buttons>
</x-form>
