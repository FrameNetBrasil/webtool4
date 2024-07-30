<x-form id="formEditUserTaskDocument" title="Document" :center="false" hx-post="/usertask/documents/new">
    <x-slot:fields>
        <x-hidden-field id="idUserTask" value="{{$idUserTask}}"></x-hidden-field>
        <x-combobox.document
            id="idDocument"
            label="Document"
            value=""
        >
        </x-combobox.document>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
