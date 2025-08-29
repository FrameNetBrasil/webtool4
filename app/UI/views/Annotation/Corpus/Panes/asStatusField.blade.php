@use(\App\Enum\Status)
<div class="status">
Status: {{$annotationSet->status}}
</div>
<div class="lome">
    @if(($annotationSet->status == Status::CREATED->value) && ($annotationSet->login == 'lome'))
        <div
            class="ui primary button"
            @click.stop="onLOMEAccepted({{$annotationSet->idAnnotationSet}})"
        >
            Accept all LOME annotations
        </div>
    @endif
</div>
