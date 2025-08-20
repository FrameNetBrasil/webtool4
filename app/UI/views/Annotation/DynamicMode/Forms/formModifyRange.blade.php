<div class="ui card form-card w-full p-1">
{{--    <div class="content">--}}
{{--        <div class="header">--}}
{{--            <x-icon::range></x-icon::range>--}}
{{--            Modify range--}}
{{--        </div>--}}
{{--        <div class="description">--}}

{{--        </div>--}}
{{--    </div>--}}
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idDocument" value="{{$object->idDocument}}">
            <input type="hidden" name="idDynamicObject" value="{{$object->idDynamicObject}}">
            <div class="two fields">
                <div class="field">
                    <label>New start frame <span class="text-primary cursor-pointer" @click="copyFrameFor('startFrame')">[Copy from video]</span></label>
                    <div class="ui medium input">
                        <input type="text" name="startFrame" placeholder="0" value="{{$object->startFrame}}">
                    </div>
                </div>
                <div class="field">
                    <label>New end frame <span class="text-primary cursor-pointer" @click="copyFrameFor('endFrame')">[Copy from video]</span></label>
                    <div class="ui medium input">
                        <input type="text" name="endFrame" placeholder="0" value="{{$object->endFrame}}">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/annotation/dynamicMode/updateObjectRange"
        >
            Save
        </button>
    </div>
</div>

{{--<form--}}
{{--    class="ui form p-4 border"--}}
{{--    hx-post="/annotation/dynamicMode/updateObjectRange"--}}
{{-->--}}
{{--    <div class="w-2/3">--}}
{{--        <input type="hidden" name="idDocument" value="{{$object->idDocument}}">--}}
{{--        <input type="hidden" name="idDynamicObject" value="{{$object->idDynamicObject}}">--}}
{{--        <div class="two fields">--}}
{{--            <div class="field">--}}
{{--                <label>New start frame <span class="text-primary cursor-pointer" @click="copyFrameFor('startFrame')">[Copy from video]</span></label>--}}
{{--                <div class="ui medium input">--}}
{{--                    <input type="text" name="startFrame" placeholder="0" value="{{$object->startFrame}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="field">--}}
{{--                <label>New end frame <span class="text-primary cursor-pointer" @click="copyFrameFor('endFrame')">[Copy from video]</span></label>--}}
{{--                <div class="ui medium input">--}}
{{--                    <input type="text" name="endFrame" placeholder="0" value="{{$object->endFrame}}">--}}
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

