@php
    $sections = [['/','Home'],['/annotation','Annotation']];
    if ($annotationType == 'staticBBox') {
        $sections[] = ['/annotation/staticBBox','Static BBox'];
    } elseif ($annotationType == 'staticEvent') {
        $sections[] = ['/annotation/staticEvent','Static event'];
    }
    $sections[] = ['',$document->name];
@endphp
<x-layout::index>
    <script type="text/javascript" src="/annotation/image/script/objects"></script>
    <script type="text/javascript" src="/annotation/image/script/components"></script>
    <div class="app-layout annotation-static-bbox">
        <x-layout::breadcrumb
            :sections="$sections"
        ></x-layout::breadcrumb>
        <div class="annotation-canvas">
            <div class="annotation-figure">
                @include("Annotation.Image.Panes.figure")
            </div>
            <div class="annotation-data">
                <div class="annotation-info">
                </div>
                <div class="annotation-forms">
                    @include("Annotation.Image.Panes.forms")
                </div>
                <div class="annotation-objects">
                    @include("Annotation.Image.Panes.grids")
                </div>
            </div>
        </div>
    </div>
</x-layout::index>
