<div style="display:flex; flex-direction: column; width:auto; padding: 8px">
    <x-form
        id="staticEventForm"
        :center="false"
    >
        <x-slot:fields>
        <x-hidden-field
                id="idDocumentSentence"
                value="{{$idDocumentSentence}}"
        ></x-hidden-field>
        <div class="flex flex-row flex-wrap gap-2">
            <x-combobox.frame
                    id="idFrame"
                    label="Choose event frame"
                    placeholder="Frame (min: 2 chars)"
                    style="width:250px"
                    class="mb-2"
            ></x-combobox.frame>
            <x-combobox.lu-event
                    id="idLU"
                    label="  or choose an event related LU:"
                    placeholder="LU (min: 2 chars)"
                    width="400px"
                    class="mb-2"
            ></x-combobox.lu-event>
            <div class="form-field">
                <div>&nbsp;</div>
                <x-button
                        id="btnSubmit"
                        label="Add Frame"
                        hx-target="#frameElementsPane"
                        hx-post="/annotation/staticEvent/addFrame"
                ></x-button>
            </div>
        </div>
        </x-slot:fields>
    </x-form>
</div>
<div id="frameElementsPane" class="pt-2">
    @include('Annotation.StaticEvent.fes')
</div>

