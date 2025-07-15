<h3 class="ui header">Create new object</h3>
<form class="ui form p-4 border">
    <div class="field">
        <x-form::combobox.layer-deixis
            label="Layer"
            id="idLayerType"
            :value="0"
            class="w-15rem"
        ></x-form::combobox.layer-deixis>
    </div>
    <div class="fields">
        <div class="field">
            <label>Start frame <span class="text-primary cursor-pointer"  @click="copyFrameFor('startFrame')">[Copy from video]</span></label>
            <input type="text" name="startFrame" placeholder="0">
        </div>
        <div class="field">
            <label>End frame  <span class="text-primary cursor-pointer"  @click="copyFrameFor('endFrame')">[Copy from video]</span></label>
            <input type="text" name="endFrame" placeholder="0">
        </div>
    </div>
    <button
        type="button"
        class="ui button"
        {{--                onclick="annotation.objects.createNewObjectAtLayer({idLayerType: document.getElementById('idLayerType').value, startFrame: Alpine.store('doStore').newStartFrame, endFrame: Alpine.store('doStore').newEndFrame})"--}}
    >Submit
    </button>
</form>

