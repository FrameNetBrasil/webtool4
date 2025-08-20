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
            <div class="two fields">
                <div class="field">
                    <label>Start frame <span class="text-primary cursor-pointer" @click="copyFrameFor('startFrame')">[Copy from video]</span></label>
                    <div class="ui medium input">
                        <input type="text" name="startFrame" placeholder="1" value="1">
                    </div>
                </div>
                <div class="field">
                    <label>End frame <span class="text-primary cursor-pointer" @click="copyFrameFor('endFrame')">[Copy from video]</span></label>
                    <div class="ui medium input">
                        <input type="text" name="endFrame" placeholder="1" value="1">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/annotation/dynamicMode/createNewObjectAtLayer"
        >
            Save
        </button>
    </div>
</div>

{{--<h3 class="ui header">Create new object</h3>--}}
{{--<form--}}
{{--    class="ui form p-4 border"--}}
{{--    hx-post="/annotation/dynamicMode/createNewObjectAtLayer"--}}
{{-->--}}
{{--    <div class="w-1/2">--}}
{{--        <input type="hidden" name="idDocument" value="{{$idDocument}}">--}}
{{--        <input type="hidden" name="idDynamicObject" value="0">--}}
{{--        <div class="two fields">--}}
{{--            <div class="field">--}}
{{--                <label>Start frame <span class="text-primary cursor-pointer" @click="copyFrameFor('startFrame')">[Copy from video]</span></label>--}}
{{--                <div class="ui medium input">--}}
{{--                    <input type="text" name="startFrame" placeholder="1" value="1">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="field">--}}
{{--                <label>End frame <span class="text-primary cursor-pointer" @click="copyFrameFor('endFrame')">[Copy from video]</span></label>--}}
{{--                <div class="ui medium input">--}}
{{--                    <input type="text" name="endFrame" placeholder="1" value="1">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <button--}}
{{--            type="submit"--}}
{{--            class="ui button medium"--}}
{{--        >Submit--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</form>--}}

