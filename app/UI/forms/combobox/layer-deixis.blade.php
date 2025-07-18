@php
    use App\Database\Criteria;
    use App\Services\AppService;

    $list = Criteria::table("view_layertype as lt")
            ->join("layergroup as lg", "lg.idLayerGroup", "=", "lt.idLayerGroup")
            ->select("lt.idLayerType", "lg.name as layerGroup", "lt.name")
            ->where("lg.type", "Deixis")
            ->where("lt.idLanguage", AppService::getCurrentIdLanguage())
            ->orderBy("lg.name")
            ->orderBy("lt.name")->all();
    $options = [];
    foreach($list as $lt) {
            $options[$lt->idLayerType] = [
                'id' => $lt->idLayerType,
                'text' => $lt->name,
            ];
    }
@endphp

@if($label != '')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div id="{{$id}}_dropdown" class="ui selection dropdown" style="overflow:initial">
    <input type="hidden" id="{{$id}}" name="{{$id}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="medium default text">Select layer</div>
    <div class="ui menu">
        @foreach($options as $option)
            <div
                data-value="{{$option['id']}}"
                class="item"
            >
                {{$option['text']}}
            </div>
        @endforeach
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({
            onChange: function(value) {
                $('#{{$id}}').val(value);
            }
        });
    });
</script>
