<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::add></x-icon::add>
            Create new Object
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idDocument" value="{{$idDocument}}">
            <input type="hidden" name="idDynamicObject" value="0">
            <input type="hidden" name="annotationType" value="dynamicMode">
            <input type="hidden" name="startFrame" x-model="currentFrame">
            <input type="hidden" name="endFrame" x-model="currentFrame">
            <button
                type="submit"
                class="ui primary button"
                hx-post="/annotation/video/createNewObjectAtLayer"
            >
                Create new object at frame <span x-text="currentFrame"></span>
            </button>
        </form>    </div>
</div>
