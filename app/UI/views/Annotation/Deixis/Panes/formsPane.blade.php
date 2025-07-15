<div
    x-data="formsComponent()"
    id="formsPane"
    class="ui pointing secondary menu tabs"
>
    <a class="item" data-tab="create-object">Create object</a>
    <a class="item" data-tab="create-bbox">Create BBox</a>
    <a class="item" data-tab="edit-object">Edit object</a>
    <a class="item" data-tab="comment">Comment</a>
</div>
<div class="gridBody">
    <div
        class="ui tab h-full w-full"
        data-tab="create-object"
    >
        @include("Annotation.Deixis.Panes.formNewObject")
    </div>
    <div
        class="ui tab h-full w-full"
        data-tab="create-bbox"
    >
        @include("Annotation.Deixis.Panes.formBBox")
    </div>
    <div
        class="ui tab h-full w-full"
        data-tab="edit-object"
    >
        @include("Annotation.Deixis.Panes.formAnnotation")
    </div>
    <div
        class="ui tab h-full w-full"
        data-tab="comment"
    >
        @include("Annotation.Deixis.Panes.formComment")
    </div>
</div>
<script type="text/javascript">
    $(".tabs .item")
        .tab()
    ;
</script>

