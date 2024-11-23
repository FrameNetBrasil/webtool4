<div class="form">
    <x-form>
        <x-slot:title>
        </x-slot:title>
        <x-slot:fields>
            <div class="field">
                <div class="title">Current Frame: <span x-text="currentFrame"></span></div>
            </div>
            <div class="field">
                <x-combobox.layer-deixis
                    label="Layer"
                    id="idLayerType"
                    :value="0"
                ></x-combobox.layer-deixis>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-button
                type="button"
                label="Create New Object"
            ></x-button>
        </x-slot:buttons>
    </x-form>
</div>
