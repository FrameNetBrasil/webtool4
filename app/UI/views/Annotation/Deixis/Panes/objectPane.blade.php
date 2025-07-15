<h3 class="ui header">Object #{{$object->idDynamicObject}} - {{$object->nameLayerType}}::{{$object->name}}</h3>
<div
    id="objectPane"
    class="ui pointing secondary menu tabs"
>
    <a class="item" data-tab="edit-object" :class="isPlaying && 'disabled'">Edit object</a>
    <a class="item" data-tab="create-bbox" :class="isPlaying && 'disabled'">Create BBox</a>
    <a class="item" data-tab="comment" :class="isPlaying && 'disabled'">Comment</a>
</div>
<div class="gridBody">
    <div
        class="ui tab h-full w-full"
        data-tab="edit-object"
    >
        @include("Annotation.Deixis.Panes.formAnnotation")
    </div>
    <div
        class="ui tab h-full w-full"
        data-tab="create-bbox"
    >
        @include("Annotation.Deixis.Panes.formBBox")
    </div>
    <div
        class="ui tab h-full w-full"
        data-tab="comment"
    >
        @include("Annotation.Deixis.Panes.formComment")
    </div>
</div>
<script type="text/javascript">
    $("#objectPane .item")
        .tab()
    ;
</script>

