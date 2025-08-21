<form class="ui form">
    <div class="ui card form-card w-full p-1">
        <div class="content">
            <input type="hidden" name="idDocument" value="{{$object->idDocument}}">
            <input type="hidden" name="idObject" value="{{$object?->idObject}}">
            <input type="hidden" name="createdAt" value="{{$object?->comment->createdAt}}">
            <input type="hidden" name="annotationType" value="{{$annotationType}}">
            <div class="field mr-1">
                <textarea
                    name="comment"
                    rows="3"
                >{!! $object->comment->comment ?? '' !!}</textarea>
            </div>
        </div>
        <div class="extra content">
            <button
                type="submit"
                class="ui primary button"
                hx-post="/annotation/comment/update"
            >
                Save
            </button>
            <button
                class="ui medium button danger"
                type="reset"
                hx-delete="/annotation/comment/{{$object->comment->idAnnotationComment}}"
            >Delete
            </button>
        </div>
    </div>
</form>
