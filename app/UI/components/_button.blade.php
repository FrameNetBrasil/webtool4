@if($href == '')
    <button {{$attributes->merge(['class' => 'hxBtn hx' . $color])}} {{$attributes}}>
        @if($icon != '')
            <span class="material-icons wt-button-icon wt-icon-{{$icon}}"></span>
        @endif
        {{$label}}
        {{$slot}}
    </button>
@else
    <a href="{{$href}}" {{$attributes->merge(['class' => 'hxBtn hx' . $color])}} {{$attributes}}>
        @if($icon != '')
            <span class="material-icons wt-button-icon wt-icon-{{$icon}}"></span>
        @endif
                {!! $label !!}

    </a>
@endif
