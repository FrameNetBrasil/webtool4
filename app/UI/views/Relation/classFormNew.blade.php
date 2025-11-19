<x-form>
    <x-slot:fields>
        <x-hidden-field id="idFrame" :value="$idFrame"></x-hidden-field>
            <div class="fields">
                <div class="field">
                    <x-combobox.microframe-relation
                        id="idMicroframe"
                        group="class"
                    ></x-combobox.microframe-relation>
                </div>
                <div class="field">
                    <x-combobox.classes
                        id="idFrameRelated"
                        label="Related Class [min: 3 chars]"
                        :hasDescription="false"
                    ></x-combobox.classes>
                </div>
            </div>

    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Add Relation" hx-post="/relation/frame"></x-submit>
    </x-slot:buttons>
</x-form>
