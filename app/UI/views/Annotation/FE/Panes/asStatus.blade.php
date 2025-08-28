@use(\App\Enum\AnnotationSetStatus)
@if($annotationSetStatus->entry == AnnotationSetStatus::COMPLETE->value)
    <div class="ui success message">
        <div class="header">
            Status
        </div>
        Annotation completed.
    </div>
@endif
@if($annotationSetStatus->entry == AnnotationSetStatus::PARTIAL->value)
    <div class="ui warning message">
        <div class="header">
            Warning
        </div>
        Partial annotation.
    </div>
@endif
@if($annotationSetStatus->entry == AnnotationSetStatus::UNANNOTATED->value)
    <div class="ui error message">
        <div class="header">
            Attention
        </div>
        No annotation of Core FE.
    </div>
@endif
