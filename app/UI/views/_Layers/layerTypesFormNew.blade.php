<form class="ui form">
    <div class="ui card form-card w-full p-1">
        <div class="content">
            <input type="hidden" name="idLayerGroup" value="{{$idLayerGroup}}">

            <div class="three fields">
                <div class="field">
                    <label for="nameEn">Name (English)</label>
                    <div class="ui small input">
                        <input type="text" id="nameEn" name="nameEn" value="">
                    </div>
                </div>
                <div class="field">
                    <label for="layerOrder">Order</label>
                    <div class="ui small input">
                        <input type="number" id="layerOrder" name="layerOrder" value="1">
                    </div>
                </div>
                <div class="field">
                    <label for="allowsApositional">Allows Apositional</label>
                    <div class="ui checkbox">
                        <input type="checkbox" id="allowsApositional" name="allowsApositional" value="1">
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="field">
                <label for="isAnnotation">Is Annotation</label>
                <div class="ui checkbox">
                    <input type="checkbox" id="isAnnotation" name="isAnnotation" value="1" checked>
                    <label></label>
                </div>
            </div>
        </div>
        <div class="extra content">
            <button
                type="submit"
                class="ui primary button"
                hx-post="/layers/{{$idLayerGroup}}/layertypes/new"
            >
                Add
            </button>
        </div>
    </div>
</form>
