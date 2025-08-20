<div class="ui card form-card w-full p-1">
{{--    <div class="content">--}}
{{--        <div class="header">--}}
{{--            <x-icon::comment></x-icon::comment>--}}
{{--            Comment--}}
{{--        </div>--}}
{{--        <div class="description">--}}

{{--        </div>--}}
{{--    </div>--}}
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idDocument" value="{{$object->idDocument}}">
            <input type="hidden" name="idDynamicObject" value="{{$object?->idDynamicObject}}">
            <input type="hidden" name="createdAt" value="{{$object?->comment->createdAt}}">
            <div class="field mr-1">
                <textarea
                    name="comment"
                    rows="3"
                >{!! $object->comment->comment ?? '' !!}</textarea>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/annotation/dynamicMode/updateObjectComment"
        >
            Save
        </button>
        <button
            class="ui medium button danger"
            type="button"
            hx-delete="/annotation/dynamicMode/comment/{{$object->idDocument}}/{{$object?->idDynamicObject}}"
        >Delete
        </button>
    </div>
</div>


{{--<form--}}
{{--    class="ui form p-4 border"--}}
{{--    hx-post="/annotation/dynamicMode/updateObjectComment"--}}
{{-->--}}
{{--    <input type="hidden" name="idDocument" value="{{$object->idDocument}}">--}}
{{--    <input type="hidden" name="idDynamicObject" value="{{$object?->idDynamicObject}}">--}}
{{--    <input type="hidden" name="createdAt" value="{{$object?->comment->createdAt}}">--}}
{{--    <div class="field mr-1">--}}
{{--        <x-form::multiline-field--}}
{{--            label="Comment"--}}
{{--            id="comment"--}}
{{--            rows="4"--}}
{{--            :value="$object->comment->comment ?? ''"--}}
{{--        ></x-form::multiline-field>--}}
{{--    </div>--}}
{{--    <button type="submit" class="ui medium button">--}}
{{--        Save--}}
{{--    </button>--}}
{{--    <button--}}
{{--        class="ui medium button danger"--}}
{{--        type="button"--}}
{{--        hx-delete="/annotation/dynamicMode/comment/{{$object->idDocument}}/{{$object?->idDynamicObject}}"--}}
{{--    >Delete--}}
{{--    </button>--}}
{{--</form>--}}
