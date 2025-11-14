{{--
    Class Overview Component - Display class identification metadata
    Parameters:
    - $class: Class object with idClass, idEntity, name, description
--}}
<div class="page-metadata">
    <div class="metadata-left">
        {{-- Add semantic type badge if needed --}}
    </div>

    <div class="metadata-right">
        <div class="ui label wt-tag-id">
            #{{ $class->idClass }}
        </div>

        @if(isset($class->idEntity))
            <div class="ui label wt-tag-entity">
                Entity: {{ $class->idEntity }}
            </div>
        @endif
    </div>
</div>
