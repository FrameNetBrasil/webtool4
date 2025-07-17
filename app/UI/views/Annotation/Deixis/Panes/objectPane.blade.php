<div
    x-data="objectComponent({!! Js::from($object) !!} ,'{{ csrf_token() }}')"
>
    <div class="flex-container justify-between">
        <div>
            <h3 class="ui header">Object #{{$object->idDynamicObject}} - {{$object->nameLayerType}}</h3>
        </div>
        <div>
            <button
                id="btnClose"
                class="ui small icon button"
                title="Close Object"
                @click="window.location.assign('/annotation/deixis/{{$object->idDocument}}')"
            >
                <i class="pt-1 close icon"></i>
            </button>
        </div>
    </div>
    <div
        class="objectPane ui pointing secondary menu tabs mt-0"
    >
        <a class="item" data-tab="edit-object" :class="isPlaying && 'disabled'">Edit object</a>
        <a class="item" data-tab="create-bbox" :class="isPlaying && 'disabled'">BBox</a>
        <a class="item" data-tab="comment" :class="isPlaying && 'disabled'">Comment</a>
    </div>
    <div class="gridBody">
        <div
            class="ui tab h-full w-full active"
            data-tab="edit-object"
        >
            @include("Annotation.Deixis.Forms.formAnnotation")
        </div>
        <div
            class="ui tab h-full w-full"
            data-tab="create-bbox"
        >
            @include("Annotation.Deixis.Forms.formBBox")
        </div>
        <div
            class="ui tab h-full w-full"
            data-tab="comment"
        >
            @include("Annotation.Deixis.Forms.formComment")
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $(".objectPane .item")
                .tab()
            ;
            document.dispatchEvent(new CustomEvent("video-seek-frame", { detail: { frameNumber: {{$object->startFrame}} } }));
        });
    </script>

</div>
