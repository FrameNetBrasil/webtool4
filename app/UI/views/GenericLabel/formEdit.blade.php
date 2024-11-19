<x-form
    title=""
    hx-put="/genericlabel"
>
    <x-slot:fields>
        <x-hidden-field id="idGenericLabel" value="{{$genericLabel->idGenericLabel}}"></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value="{{$genericLabel->name}}"
            ></x-text-field>
        </div>
        <div class="field">
            <x-multiline-field
                label="Definition"
                id="definition"
                value="{{$genericLabel->definition}}"
            ></x-multiline-field>
        </div>
        <div class="two fields">
            <div class="field">
                <x-combobox.language
                    id="idLanguage"
                    label="Language"
                    :value="$genericLabel->idLanguage"
                ></x-combobox.language>
            </div>
            <div class="field">
                <x-combobox.color
                    id="idColor"
                    label="Color"
                    :value="$genericLabel->idColor"
                    placeholder="Color"
                ></x-combobox.color>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
