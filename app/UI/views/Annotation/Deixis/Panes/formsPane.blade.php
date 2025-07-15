<div
    id="formsPane"
    x-data="formsComponent()"
    @video-update-state.document="onVideoUpdateState"
    @object-selected.document="onObjectSelected"
>
    @include("Annotation.Deixis.Panes.formNewObject")
</div>
