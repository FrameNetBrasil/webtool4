@php

    use App\Database\Criteria;

    $value = $value ?? $nullName ?? '';
    $options = [];
    if ($idFrame > 0) {
        $filter = [["idFrame", "=", $idFrame]];
        if (isset($coreType)) {
            $filter[] = ["coreType", "IN", $coreType];
        }
        $fes = Criteria::byFilterLanguage("view_frameelement", $filter)->all();
        if (isset($hasNull)) {
            $options[] = [
                'idFrameElement' => '-1',
                'name' => $nullName ?? "NULL",
                'coreType' => '',
                'idColor' => "color_1"
            ];
        }
        foreach ($fes as $fe) {
            if ($value == $fe->idFrameElement) {
                $defaultText = $fe->name;
            }
            $options[] = [
                'idFrameElement' => $fe->idFrameElement,
                'name' => $fe->name,
                'coreType' => $fe->coreType,
                'idColor' => $fe->idColor
            ];
        }
    }
@endphp


@if($label!='')
    <label for="{{$name}}">{{$label}}</label>
@endif
<div
    class="ui clearable selection dropdown frameElement"
    style="overflow:initial;"
    x-init="$($el).dropdown()"
>
    <input type="hidden" name="{{$name}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="default text">{{$defaultText ?? ''}}</div>
    <div class="menu">
        @foreach($options as $fe)
            <div
                data-value="{{$fe['idFrameElement']}}"
                class="item p-1 min-h-0"
            >
                @if($fe['coreType'] != '')
                    <x-ui::element.fe
                        name="{{$fe['name']}}"
                        type="{{$fe['coreType']}}"
                        idColor="{{$fe['idColor']}}"
                    ></x-ui::element.fe>
                @else
                    <span>{{$fe['name']}}</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
