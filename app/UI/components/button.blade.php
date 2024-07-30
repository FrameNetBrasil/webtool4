@if($href == '')
    <button {{$attributes->merge(['class' => 'ui ' . $color .' button'])}} {{$attributes}}>
        @if($icon != '')
            <i class="icon material">{{$icon}}</i>
        @endif
        {{$label}}
        {{$slot}}
    </button>
@else
    <a href="{{$href}}" {{$attributes->merge(['class' => 'ui ' . $color .' button'])}} {{$attributes}}>
        @if($icon != '')
            <span class="material-icons wt-button-icon wt-icon-{{$icon}}"></span>
        @endif
                {!! $label !!}

    </a>
@endif
