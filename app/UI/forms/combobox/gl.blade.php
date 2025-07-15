@php
    use App\Database\Criteria;

    $name ??= $id;
    $value = $value ?? $this->nullName ?? '';
    $options = [];
    if ($idLayerType > 0) {
        $filter = [["idLayerType", "=", $idLayerType]];
        $gls = Criteria::byFilterLanguage("genericlabel", $filter)->all();
        if ($hasNull) {
            $options[] = [
                'idGenericLabel' => '-1',
                'name' => $nullName ?? "NULL",
                'idColor' => "color_1"
            ];
        }
        foreach ($gls as $gl) {
            if ($value == $gl->idGenericLabel) {
                $default = $gl->name;
            }
            $options[] = [
                'idGenricLabel' => $gl->idGenericLabel,
                'name' => $gl->name,
                'idColor' => $gl->idColor
            ];
        }
    }
@endphp

@if($label!='')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div id="{{$id}}_dropdown" class="ui clearable selection dropdown frameElement" style="overflow:initial;">
    <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="default text">{{$defaultText ?? ''}}</div>
    <div class="menu">
        @foreach($options as $option)
            <div data-value="{{$option['idGenricLabel']}}"
                 class="item p-1 min-h-0">
                <x-element.gl name="{{$option['name']}}" idColor="{{$option['idColor']}}"></x-element.gl>
            </div>
        @endforeach
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({
            onChange: (value) => { {!! $onChange ?? '' !!} }
        });
    });
</script>
