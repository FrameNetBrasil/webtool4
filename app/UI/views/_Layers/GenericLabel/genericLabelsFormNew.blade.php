<form class="ui form">
    <div class="ui card form-card w-full p-1">
        <div class="content">
            <input type="hidden" name="idLayerType" value="{{$idLayerType}}">

            <div class="two fields">
                <div class="field">
                    <label for="name">Name</label>
                    <div class="ui small input">
                        <input type="text" id="name" name="name" value="">
                    </div>
                </div>
                <div class="field">
                    <x-combobox.color
                        id="idColor"
                        label="Color"
                        :value="0"
                    ></x-combobox.color>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <x-combobox.language
                        id="idLanguage"
                        label="Language"
                        :value="2"
                    ></x-combobox.language>
                </div>
                <div class="field">
                    <label for="definition">Definition</label>
                    <div class="ui small input">
                        <input type="text" id="definition" name="definition" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="extra content">
            <button
                type="submit"
                class="ui primary button"
                hx-post="/layertype/{{$idLayerType}}/genericlabels/new"
            >
                Add
            </button>
        </div>
    </div>
</form>
