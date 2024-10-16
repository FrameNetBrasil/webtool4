<div
    {{$attributes->class(["ui form wt-form","wt-container-center-content" => $center,"wt-form-noborder" => !$border])->whereDoesntStartWith('hx-')}} style="overflow:initial">
    <form id="{{$id}}" name="{{$id}}" {{$attributes}}>
        @if(isset($header))
            <div class="form-header">
                {{ $header }}
            </div>
        @else
            @if($title != '')
                <div class="form-header">
                    <div class="form-title">{{$title}}</div>
                </div>
            @endif
        @endif
        <div class="form-toolbar">
            {{$toolbar}}
        </div>
        <div id="{{$id}}_fields" class="form-fields">
            {{$fields}}
        </div>
            @if(isset($buttons))
        <div class="form-buttons">
            {{$buttons}}
        </div>
            @endif
    </form>
</div>

