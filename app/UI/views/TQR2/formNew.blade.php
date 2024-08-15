<x-form id="formNewTQR2" title="New Structure" :center="false"  hx-post="/tqr2/new">
    <x-slot:fields>
        <x-combobox.frame
            id="idFrame"
            label="Background Frame to (min 3 chars)"
            placeholder=""
        ></x-combobox.frame>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
