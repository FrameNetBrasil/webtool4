<div class="ui form">
    <div class="fields">
        <div class="field">
            <div class="title">Current Frame: <span x-text="currentFrame"></span></div>
        </div>
        <div class="field">
            <x-combobox.layer-deixis
                label=""
                id="idLayerType"
                :value="0"
            ></x-combobox.layer-deixis>
        </div>
        <div class="field">
            <x-button
                type="button"
                label="Create New Object"
                onclick="annotation.objects.createNewObjectAtLayer({idLayerType: document.getElementById('idLayerType').value,currentFrame: Alpine.store('doStore').currentFrame})"
            ></x-button>
        </div>
    </div>
</div>
