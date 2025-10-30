<form class="ui form">
    <div class="ui card form-card w-full p-1">
        <div class="content">
            <input type="hidden" name="idLayerType" value="{{$layerType->idLayerType}}">

            <div class="two fields">
                <div class="field">
                    <label for="layerOrder">Layer Order</label>
                    <div class="ui small input">
                        <input type="number" id="layerOrder" name="layerOrder" value="{{$layerType->layerOrder}}">
                    </div>
                </div>
                <div class="field">
                    <label>Flags</label>
                    <div class="ui segment">
                        <div class="ui checkbox">
                            <input type="checkbox" id="allowsApositional" name="allowsApositional" value="1" {{$layerType->allowsApositional ? 'checked' : ''}}>
                            <label for="allowsApositional">Allows Apositional</label>
                        </div>
                        <div class="ui checkbox mt-2">
                            <input type="checkbox" id="isAnnotation" name="isAnnotation" value="1" {{$layerType->isAnnotation ? 'checked' : ''}}>
                            <label for="isAnnotation">Is Annotation</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="extra content">
            <button
                type="submit"
                class="ui primary button"
                hx-put="/layertype"
            >
                Save
            </button>
        </div>
    </div>
</form>
