<form
    class="ui form p-4 border"
    hx-target="this"
>
    <input type="hidden" name="idAnnotationSet" value="{{$comment->idAnnotationSet}}">
    <input type="hidden" name="createdAt" value="{{$comment?->createdAt}}">
    <div class="d-flex">
        <div class="title">Comment for AnnotationSet #{{$comment->idAnnotationSet}}</div>
        @if($comment->email)
            <div class="text-sm">Created by [{{$comment->email}}] at [{{$comment->createdAt}}]</div>
        @endif
    </div>
    <div class="field mr-1">
        <textarea
             name="comment"
             rows="4"
        >{!! $comment->comment ?? '' !!}</textarea>
    </div>
    <button
        type="submit"
        class="ui medium button"
        hx-post="/annotation/fe/updateObjectComment"
    >
        Save
    </button>
    <button
        class="ui medium button danger"
        type="button"
        hx-delete="/annotation/fe/comment/{{$comment->idAnnotationSet}}"
    >Delete
    </button>
</form>


{{--<form--}}
{{--    class="ui form">--}}
{{--    <x-form--}}
{{--        hx-post="/annotation/fe/updateObjectComment"--}}
{{--    >--}}
{{--        <x-slot:title>--}}
{{--            <div class="flex gap-2">--}}
{{--                <div class="title">Comment for AnnotationSet #{{$comment->idAnnotationSet}}</div>--}}
{{--                @if($comment->email)--}}
{{--                    <div class="text-sm">Created by [{{$comment->email}}] at [{{$comment->createdAt}}]</div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </x-slot:title>--}}
{{--        <x-slot:fields>--}}
{{--            <x-hidden-field id="idAnnotationSet" value="{{$comment->idAnnotationSet}}"></x-hidden-field>--}}
{{--            <x-hidden-field id="createdAt" value="{{$comment?->createdAt}}"></x-hidden-field>--}}
{{--            <div class="field mr-1">--}}
{{--                <x-multiline-field--}}
{{--                    label="Comment"--}}
{{--                    id="comment"--}}
{{--                    :value="$comment->comment ?? ''"--}}
{{--                ></x-multiline-field>--}}
{{--            </div>--}}
{{--        </x-slot:fields>--}}
{{--        <x-slot:buttons>--}}
{{--            <x-submit label="Save"></x-submit>--}}
{{--            <x-button--}}
{{--                type="button"--}}
{{--                label="Delete"--}}
{{--                color="danger"--}}
{{--                onclick="messenger.confirmDelete(`Removing Comment for #{{$comment->idAnnotationSet}}'.`, '/annotation/fe/comment/{{$comment->idAnnotationSet}}', null, '')"--}}
{{--            ></x-button>--}}
{{--        </x-slot:buttons>--}}
{{--    </x-form>--}}
{{--</form>--}}

