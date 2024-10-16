<x-form
    title="Edit image"
    hx-post="/image"
>
    <x-slot:fields>
        <x-hidden-field
            id="idImage"
            :value="$image->idImage"
        ></x-hidden-field>
        <div class="formgrid grid">
            <div class="field col">
                <x-text-field
                    label="Name"
                    id="name"
                    :value="$image->name"
                ></x-text-field>
            </div>
            <div class="field col">
                <x-text-field
                    label="Current URL"
                    id="currentURL"
                    :value="$image->currentURL"
                ></x-text-field>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
