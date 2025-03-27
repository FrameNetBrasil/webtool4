<div id="workArea" class="workArea">
    @foreach($annotationSets as $i => $annotationSet)
        <div
            hx-trigger="load"
            hx-get="/annotation/as/as/{{$annotationSet->idAnnotationSet}}"
            hx-swap="innerHTML"
        >
        </div>
    @endforeach
</div>
